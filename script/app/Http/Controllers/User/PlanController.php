<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Lib\Flutterwave;
use App\Lib\Paypal;
use App\Lib\Mollie;
use App\Lib\Instamojo;
use App\Lib\Paystack;
use App\Lib\Razorpay;
use App\Lib\Toyyibpay;
use App\Mail\PlanMail;
use App\Models\Getway;
use App\Models\Option;
use App\Models\Ordermeta;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\User;
use App\Models\Userplan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $plans = Getway::where('status', 1)->with('term')->get(); 
        // return view('user.plan.index', compact('gateways'));
    }

    public function subscription(){
        $plans = Plan::where([['status', 1],['is_default',0],['is_trial',0]])->get();
        $orders = Orders::where('user_id', Auth::id())->with('plan', 'getway')->latest()->paginate(25);
        return view('user.plan.subscription',compact('plans','orders')); 
    }


    public function gateways($planid)
    {
        if (Session::has('plan_id')) {
            Session::forget('plan_id');
        }
        $gateways = Getway::where('status', 1)->where('name','!=', 'free')->get(); 
        $all_currency = Option::where('key', 'currency')->get();
        $tax = Option::where('key','tax')->first();
        $plan = Plan::findOrFail($planid);
        return view('user.plan.gateways', compact('gateways','all_currency','plan','tax'));
    }

    public function deposit(Request $request){     
        $gateway = Getway::findOrFail($request->id);
        if($gateway->phone_status == 1){ 
            $request->validate([
                'phone' => 'required',
            ],
            [
                'phone.required' => 'Phone number is required for : ' . ucwords($gateway->name),
            ]
        );
            
        }
     
        if ($gateway->id == 10 ) {
            $request->validate([
                'screenshot' => 'required|image|max:1000',
                'comment' => 'required|max:200'
                ]);
                
             $screenshot = $request->file('screenshot');
             $receipt = hexdec(uniqid()) . '.' . $screenshot->getClientOriginalExtension();
             $path = 'uploads/'.Auth::id().'/'. date('/y/m/');
             $screenshot->move($path, $receipt);
             $image = $path . $receipt;
             $payment_data['screenshot'] =$image;
             $payment_data['comment'] =$request->comment;
        }
        $gateway_info = json_decode($gateway->data); //for creds
        $tax = Option::where('key','tax')->first();
        $plan = Plan::where([['status', 1], ['is_default', 0],['is_trial', 0]])->findOrFail($request->plan_id);
        $payment_data['currency'] = $gateway->currency ?? 'USD';
        $payment_data['email'] = Auth::user()->email;
        $payment_data['name'] = Auth::user()->name;
        $payment_data['phone'] = $request->phone;
        $payment_data['billName'] = $plan->name;
        $payment_data['amount'] = $gateway->charge+$plan->price * $gateway->rate;
        $payment_data['test_mode'] = $gateway->test_mode;
        $payment_data['charge'] = $gateway->charge ?? 0;
        $payment_data['pay_amount'] = (round($plan->price + (($plan->price / 100) * $tax->value), 2) * $gateway->rate) + $gateway->charge;
        $payment_data['getway_id'] = $gateway->id;
        $payment_data['payment_type'] = 1;
        $payment_data['request'] = $request->except('_token');
        $payment_data['request_from'] = 'merchant';
        
        Session::put('plan', $request->plan_id);

        if (!empty($gateway_info)) {
            foreach ($gateway_info as $key => $info) {
                $payment_data[$key] = $info;
            };
        }

       return  $gateway->namespace::make_payment($payment_data);       
    }

    public function fail(){
        Session::flash('message', 'Transaction Error Occured!');
        Session::flash('type', 'danger');
        return redirect()->route('user.plan.subscribe');
    }

    public function success(Request $request){
        abort_if(!Session::has('payment_info'),403);
        $plan_id = $request->session()->get('plan');
        $plan = Plan::findOrFail($plan_id);
        $tax = Option::where('key','tax')->first();
        $getway_id = $request->session()->get('payment_info')['getway_id'];
        $amount = $request->session()->get('payment_info')['amount'];
        $payment_id = $request->session()->get('payment_info')['payment_id'];
        $screenshot = $request->session()->get('payment_info')['screenshot'] ?? '';
        $comment = $request->session()->get('payment_info')['comment'] ?? '';
        $status = $request->session()->get('payment_info')['status'] ?? '';
        $payment_status = $request->session()->get('payment_info')['payment_status'] ?? 0;

        $withTaxTotalAmount = $amount + (($amount / 100) * $tax->value);
        $auto_enrollment = Option::where('key','auto_enroll_after_payment')->pluck('value')->first();
        $order = new Orders();
        $order->status = 3;

        $option = Option::where('key','cron_option')->first();
        $invoice_prefix = Option::where('key','invoice_prefix')->first();
        $cron_option = json_decode($option->value);

        if ($auto_enrollment == 'on' && $status == 1) {
            $userplan = Userplan::where('user_id', Auth::user()->id)->first();
            if (!$userplan) {
                $userplan = new Userplan;
            }

            DB::beginTransaction();
            try {
                $userplan->name = $plan->name;
                $userplan->user_id = Auth::user()->id;
                $userplan->storage_size = $plan->storage_size;
                $userplan->project_limit = $plan->project_limit;
                $userplan->user_limit = $plan->user_limit;
                $userplan->group_limit = $plan->group_limit;
                $userplan->gps = $plan->gps;
                $userplan->mail_activity = $plan->mail_activity;
                $userplan->adminable_project = $plan->adminable_project;            
                $userplan->screenshot = $plan->screenshot;
                $userplan->save();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }

        $admin = User::where('role_id',1)->first();

        $data = [
            'type' => 'plan',
            'email' => $admin->email,
            'name' =>  Auth::user()->name,
            'message' => "Successfully Paid " . round($withTaxTotalAmount, 2) . " (tax included) for " . $plan->name . " plan"
        ];

    
        Mail::to($admin->email)->send(new PlanMail($data)); 


        DB::beginTransaction();
        try {
            // Insert transaction data into deposit table  
            $order->plan_id = $plan->id;
            $order->getway_id = $getway_id;
            $order->user_id = Auth::user()->id;
            $order->tax = $tax->value;
            $order->amount = $plan->price;    
            $order->payment_id = $payment_id;
            $order->status = ($auto_enrollment == 'on' && $getway_id != 10 && $status == 1) ? 1 : 3;  
            $order->payment_status = $payment_status;  
            $order->will_expire = Carbon::now()->addDays($plan->duration);
            $order->save();
    
            $ordermeta = new Ordermeta;
            $ordermeta->key = 'order_info';
            $ordermeta->order_id = $order->id;
            $ordermeta->value = json_encode(['screenshot' => $screenshot, 'comment' =>  $comment]);
            $ordermeta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        Orders::where('id',$order->id)->update(array('invoice_id' => $invoice_prefix->value . $order->id));

        Session::flash('message', 'Transaction Successfully done!');
        Session::flash('type', 'success');
        Session::forget('payment_info');
        return redirect()->route('user.plan.subscribe');
    }

    public function history(){
        return view('user.plan.history');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
