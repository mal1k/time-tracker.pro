<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\OrderMail;
use App\Mail\OrderMailExpired;
use App\Mail\PlanMail;
use App\Models\Getway;
use App\Models\Option;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\User;
use App\Models\Userplan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$string='')
    {
        abort_if(!Auth()->user()->can('order.index'), 401);
        $orders = Orders::with('plan','user')->latest()->paginate(10);
        $allorders = $orders->total();
        $approved = Orders::where('status', 1)->count();
        $cancelled = Orders::where('status', 0)->count();
        $pending = Orders::where('status', 3)->count();
        $trash = Orders::where('status', 4)->count();
        
        return view('admin.order.index', compact('orders','approved','cancelled','pending','string','allorders','trash','request'));
    }

    public function search(Request $request, $string=''){
        if($string === 'approved'){
            $orders = Orders::with('plan','user')->where('status', 1)->latest()->paginate(10);
        }elseif ($string === 'pending') {
            $orders = Orders::with('plan','user')->where('status', 3)->latest()->paginate(10);
        }elseif ($string === 'cancel') {
            $orders = Orders::with('plan','user')->where('status', 0)->latest()->paginate(10);
        }elseif ($string === 'trash') {
           $orders = Orders::with('plan','user')->where('status', 4)->latest()->paginate(10);
        }
        else{
            $orders = Orders::with('plan','user')->latest()->paginate(10);
        }

        $allorders = Orders::count();
        
        if ($request->filter == 'invoice') {
            $orders = Orders::with('plan','user')->where('invoice_id','LIKE','%'. $request->q . '%')->paginate(10);
        }elseif ($request->filter == 'payment_id') {
            $orders = Orders::with('plan','user')->where('payment_id','LIKE','%'. $request->q . '%')->paginate(10);
        }elseif ($request->filter == 'email') {
            $str = $request->q;
            $orders = Orders::with('plan','user')->whereHas('user',function($q) use ($str){
                $q->where('email','LIKE','%'. $str . '%');
            })->paginate(10);
        }
       
        $approved = Orders::where('status', 1)->count();
        $cancelled = Orders::where('status', 0)->count();
        $pending = Orders::where('status', 3)->count();
        $trash = Orders::where('status', 4)->count();
        
        return view('admin.order.index', compact('orders','approved','cancelled','pending','string','allorders','trash','request'));
    }

    public function status(Request $request){
       if ($request->ids && count($request->ids) > 0 && $request->status != null) {
            if($request->status == 5){
                Orders::whereIn('id',$request->ids)->delete();
            }
            else{
                Orders::whereIn('id',$request->ids)->update(['status'=>$request->status]);
            }
            return back();
       }

       return back()->with('alert','Nothing selected!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!Auth()->user()->can('order.create'), 401);
        $plans = Plan::where('status', 1)->get();
        $getways = Getway::where('status', 1)->get();
        return view('admin.order.create',compact('plans', 'getways'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'plan_id'           => 'required',
            'getway_id' => 'required',
            'email'         => 'required',
            'payment_id'         => 'required',
            
        ]);

        $user = User::where('email',$request->email)->first();
        $plan = Plan::where('id',$request->plan_id)->first();
        $getway = Getway::where('id',$request->getway_id)->first();
        $invoice = Option::where('key','invoice_prefix')->first();
        $tax = Option::where('key','tax')->first();
        if(!$user)
        {
            $msg['errors']['error']="User Not Found";
            return response()->json($msg,401);
        }


        DB::beginTransaction();
        try {
            $order = new Orders;
            $order->plan_id = $request->plan_id;
            $order->user_id = $user->id;
            $order->getway_id = $request->getway_id;
            $order->payment_id = $request->payment_id;
            $order->amount = $plan->price;
            $order->tax = $tax->value;
            $order->status = 1;  
            $order->will_expire = Carbon::now()->addDays($plan->duration);
            $order->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        Orders::where('id', $order->id)->update(array('invoice_id'=>$invoice->value. $order->id));

        $userplan = Userplan::where('user_id', $order->user_id)->first();

        if (!$userplan) {
           $userplan = new Userplan;
        }

        DB::beginTransaction();
        try {
            $userplan->name = $plan->name;
            $userplan->user_id = $order->user_id;
            $userplan->storage_size = $plan->storage_size;
            $userplan->project_limit = $plan->project_limit;
            $userplan->user_limit = $plan->user_limit;
            $userplan->group_limit = $plan->group_limit;
            $userplan->gps = $plan->gps;
            $userplan->screenshot = $plan->screenshot;
            $userplan->is_featured = $plan->is_featured;
            $userplan->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        return response()->json('Order Added Successfully');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $order = Orders::findOrFail($id);
        return view('admin.order.show',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plans = Plan::where('status', 1)->get();
        $getways = Getway::where('status', 1)->get();
        $order = Orders::findOrFail($id);
        return view('admin.order.edit',compact('order','plans','getways'));
    }
    
    public function active($id){
        abort_if(!Auth()->user()->can('order.active'), 401);

        DB::beginTransaction();
        try {
            $order = Orders::findOrFail($id);
            $plan = Plan::findOrFail($order->plan_id);
            $option = Option::where('key','cron_option')->first();
            $getway = Getway::findOrFail($order->getway_id);
            $cron_option = json_decode($option->value);
            $user = User::findOrFail($order->user_id);
            $order->status = 1;  
            $order->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        $tax = Option::where('key','tax')->first();
        $withTaxTotalAmount = $plan->price + (($plan->price / 100) * $tax->value);

        $userplan = Userplan::where('user_id', $order->user_id)->first();

        if (!$userplan) {
           $userplan = new Userplan;
        }

        DB::beginTransaction();
        try {
            $userplan->name = $plan->name;
            $userplan->user_id = $order->user_id;
            $userplan->storage_size = $plan->storage_size;
            $userplan->project_limit = $plan->project_limit;
            $userplan->user_limit = $plan->user_limit;
            $userplan->group_limit = $plan->group_limit;
            $userplan->gps = $plan->gps;
           
            $userplan->screenshot = $plan->screenshot;
            $userplan->is_featured = $plan->is_featured;
            $userplan->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }


        $data = [
            'type' => 'order',
            'email' => $user->email,
            'name' =>  $user->name,
            'price' => $plan->price,
            'tax' => $tax->value,
            'total' => round($withTaxTotalAmount, 2),
            'plan' => $plan->name,
            'payment_id' => $order->payment_id,
            'payment_getway' => $getway->name,
            'invoice_id' => $order->invoice_id,
        ];
       
        if (env('QUEUE_MAIL') == 'on') {
            dispatch(new SendEmailJob($data));
        }else{
            Mail::to($user->email)->send(new OrderMail($data)); 
        }

        return redirect()->back()->with('message','Order Activate successfully!');
    }

    public function deactive($id){
        abort_if(!Auth()->user()->can('order.deactive'), 401);
        $order = Orders::findOrFail($id);
        $order->status = 0;
        $order->save();
        return redirect()->back()->with('message','Order Activate successfully!');
    }


    public function alertUserAfterExpiredOrder(){
       // abort_if(!Auth()->user()->can('order.alert'), 401);
        $expirable_order = Orders::where('will_expire','<=' ,Carbon::today())->where('status', 1)->get();
        $expirable_userids = [];
        foreach ($expirable_order as $value) {
            array_push($expirable_userids, $value->user_id);
        }

        Orders::where('will_expire','<=' ,Carbon::today())->update(array('status' => 2)); //expired
        $option = Option::where('key','cron_option')->first();
        $cron_option = json_decode($option->value);
        $users = User::where('role_id', 2)->withCount('active_orders','orders')->get();
        $plan = Plan::where('is_default', 1)->first();
        $ids = [];
        $emails = [];
        $names = [];

        
        foreach ($users as $value) {
           
            if ($value->active_orders_count == 0 && in_array($value->id, $expirable_userids)) {
               array_push($ids, $value->id);
               array_push($emails, $value->email);
               array_push($names, $value->name);
            }
           
        }

        if ($cron_option->assign_default_plan == "on" && !empty($ids)) {
            $data = [
                'name' => $plan->name ?? 'deafult',
                'user_id' => $value->id,
                'storage_size' => $plan->storage_size ?? 0,
                'project_limit' => $plan->project_limit ?? 0,
                'user_limit' => $plan->user_limit ?? 0,
                'group_limit' => $plan->group_limit ?? 0,
                'gps' => $plan->gps ?? 0,
                'screenshot' => $plan->screenshot ?? 0,
                'is_featured' => $plan->is_featured ?? 0
            ];
            DB::beginTransaction();
            try {
                Userplan::whereIn('user_id', $ids)->update($data);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }
        
        if ($emails) {
            foreach ($emails as $key => $value) {
                $mail_data = [
                    'type' => 'order_expired',
                    'email' => $value,
                    'name' =>  $names[$key],
                    'message' => $cron_option->expire_message
                ];
    
                if (env('QUEUE_MAIL') == 'on') {
                    dispatch(new SendEmailJob($mail_data));
                }else{
                    Mail::to($value)->send(new OrderMailExpired($mail_data)); 
                }
            }
        }

        return "success";
    }

    public function alertUserBeforeExpiredOrder(){
      //  abort_if(!Auth()->user()->can('order.alert'), 401);
        $option = Option::where('key','cron_option')->first();
        $cron_option = json_decode($option->value);
        $expiry_date = Carbon::now()->addDays($cron_option->days - 2)->format('Y-m-d');
        $orders = Orders::where('will_expire', '>=', $expiry_date)->where('status', 1)->get(); //before expired how many days left
        $option = Option::where('key','cron_option')->first();
        $cron_option = json_decode($option->value);

        foreach ($orders as $value) {
            $user = User::findOrFail($value->user_id);
            $data = [
                'type' => 'order_expired_alert',
                'email' => $user->email,
                'name' =>  $user->name,
                'message' => $cron_option->alert_message
            ];
       
            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new SendEmailJob($data));
            }else{
                Mail::to($user->email)->send(new OrderMailExpired($data)); 
            }
        }

        return "success";
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
        $plan = Plan::findOrFail($request->plan_id);
        $getway = Getway::findOrFail($request->getway_id);
        $user = User::where('email',$request->email)->first();
        $tax = Option::where('key','tax')->first();
        $order = Orders::findOrFail($id);


        DB::beginTransaction();
        try {
            $order->plan_id = $request->plan_id;
            $order->user_id = $user->id;
            $order->getway_id = $request->getway_id;
            $order->payment_id = $request->payment_id;
            $order->amount = $plan->price;
            $order->tax = $tax->value;
            $order->status = 1;  
            $order->will_expire = Carbon::now()->addDays($plan->duration);
            $order->status = $request->status;  
            $order->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        $withTaxTotalAmount = $plan->price + (($plan->price / 100) * $tax->value);
        $userplan = Userplan::where('user_id', $order->user_id)->first();

        if (!$userplan) {
           $userplan = new Userplan;
        }


        DB::beginTransaction();
        try {
            $userplan->name = $plan->name;
            $userplan->user_id = $order->user_id;
            $userplan->storage_size = $plan->storage_size;
            $userplan->project_limit = $plan->project_limit;
            $userplan->user_limit = $plan->user_limit;
            $userplan->group_limit = $plan->group_limit;
            $userplan->gps = $plan->gps;
          
            $userplan->screenshot = $plan->screenshot;
            $userplan->is_featured = $plan->is_featured;
            $userplan->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }


        if ($request->email_status == '1') {
            $data = [
                'type' => 'order',
                'email' => $user->email,
                'name' =>  $user->name,
                'price' => $plan->price,
                'tax' => $tax->value,
                'total' => round($withTaxTotalAmount, 2),
                'plan' => $plan->name,
                'payment_id' => $order->payment_id,
                'payment_getway' => $getway->name,
                'invoice_id' => $order->invoice_id,
            ];
           
            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new SendEmailJob($data));
            }else{
                Mail::to($user->email)->send(new OrderMail($data)); 
            }
        }


        return response()->json('Updated Successfully!');
        // return redirect()->back()->with('message','Order Updated successfully!');
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