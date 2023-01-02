<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\UserMail;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\Project;
use App\Models\Projectgroup;
use App\Models\Projectuser;
use App\Models\Team;
use App\Models\Time;
use App\Models\User;
use App\Models\Userplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(!Auth()->user()->can('user.index'), 401);
        $string=$request->status ?? 'all';
        if($string == 'all'){
            $users = User::where('role_id', 2);
        }
        else{
            $users = User::where('role_id', 2)->where('status',$string);
        }

        if($request->q){
            $users=$users->where($request->filter,'LIKE','%'. $request->q . '%')->paginate(20);
        }
        else{
            $users=$users->latest()->paginate(20);
        }
        
        $active_users=User::where([['role_id',2],['status',1]])->count();
        $deactive_users=User::where([['role_id',2],['status',0]])->count();
        $trash=User::where([['role_id',2],['status',2]])->count(); 
        $all=User::where([['role_id',2]])->count();
        return view('admin.user.index', compact('users','all','request','active_users','deactive_users','trash','string'));
    }

    public function status(Request $request)
    {
        if ($request->ids && count($request->ids) > 0 && $request->status != null) {
            if($request->status == 3){
                foreach ($request->ids ?? [] as $key => $id) {
                  \File::deleteDirectory('uploads/'.$id);
                   $user=User::destroy($id);
                }
                User::whereIn('id',$request->ids)->delete();
            }     
            else{
                User::whereIn('id',$request->ids)->update(['status'=>$request->status]);
            }
            return back();
       }

       return back()->with('alert','Nothing selected!');
    }

    public function login($id)
    {
        abort_if(!Auth()->user()->can('user.index'), 401);
        Auth::logout();
        Auth::loginUsingId($id);
        return redirect('/user/dashboard');
    }

    public function planEdit($id)
    {
        $info=Userplan::where('user_id',$id)->first();
        abort_if(empty($info),404);
        return view('admin.user.plan',compact('info'));
    }

    public function planUpdate(Request $request, $id)
    {
        $info=Userplan::findorFail($id);
        $info->name=$request->name;
        $info->storage_size=$request->storage_size;
        $info->project_limit=$request->project_limit;
        $info->user_limit=$request->user_limit;
        $info->group_limit=$request->group_limit;
        $info->gps=$request->gps;
        $info->mail_activity=$request->mail_activity;
        $info->adminable_project=$request->adminable_project;
        $info->screenshot=$request->screenshot; 
        $info->save();

        return response()->json('Plan Updated');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!Auth()->user()->can('user.create'), 401);
        return view('admin.user.create');
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
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6|',
            'avatar'       => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $attachment_path = $attachment_name = '';

        if ($request->hasFile('avatar')) {
            $attachment      = $request->file('avatar');
            $attachment_name = hexdec(uniqid()) . '.' . $attachment->getClientOriginalExtension();
            $attachment_path = 'uploads/' . date('y/m/');
            $attachment->move($attachment_path, $attachment_name);
        }

        // User store
        $user_store        = new User();
        $user_store->name  = $request->name;
        $user_store->email = $request->email;
        $user_store->avatar =  $attachment_path . $attachment_name;
        $user_store->password = Hash::make($request->password);
        $user_store->status         = $request->status;
        $user_store->save();

        $plan = Plan::where('is_trial', 1)->first();
        $userplan = new Userplan;
        $userplan->user_id = $user_store->id;
        if ($plan) {
            $userplan->name = $plan->name;
            $userplan->storage_size = $plan->storage_size;
            $userplan->project_limit = $plan->project_limit;
            $userplan->user_limit = $plan->user_limit;
            $userplan->group_limit = $plan->group_limit;
            $userplan->gps = $plan->gps;
         
            $userplan->screenshot = $plan->screenshot;
            $userplan->is_featured = $plan->is_featured;
        }else{
            $userplan->name = "";
            $userplan->storage_size = 0;
            $userplan->project_limit = 0;
            $userplan->user_limit = 0;
            $userplan->group_limit = 0;
            $userplan->gps = 0;
          
            $userplan->screenshot = 0;
            $userplan->is_featured = 0;
        }
       
        $userplan->save();

        return response()->json('User Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $user = User::findorFail($id);
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(!Auth()->user()->can('user.edit'), 401);
        $user = User::findOrfail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function details(Request $request){
        // Total spends of amount
        $ids = Project::where('user_id', $request->user_id)->pluck('id');
        $order_amount = Orders::where('user_id', $request->user_id)->sum('amount');
        $data['amount'] = $order_amount + Time::whereIn('project_id', $ids)->sum('price');
        // total orders
        $data['order_list'] = Orders::with('plan','getway')->where('user_id', $request->user_id)->get();
        $data['orders'] = count($data['order_list']);

        // storage used current plan
        $storage = 0;
        foreach ($ids as $project_id){
            $storage += $this->storageSize($request->user_id, $project_id);
        }

        $storage_limit = Userplan::where('user_id',$request->user_id)->pluck('storage_size')->first();
        $data['storage'] = $storage;
        $data['percentage'] = $storage_limit > 0 ? (($storage / $storage_limit) * 100) : 0;
        // total team
        $data['team'] = Team::where('creator_id', $request->user_id)->count();
        // total members
        $data['members'] = Projectuser::whereIn('project_id', $ids)->where('user_id','!=' ,$request->user_id)->groupBy('user_id')->count();
        // total groups
        $data['groups'] = Projectgroup::whereIn('project_id', $ids)->count();
        // total projects 
        $data['projects'] = count($ids);

        
        return $data;
    }

    public function invoice($id){
        abort_if(!Auth()->user()->can('user.invoice'), 401);
        $invoice = Orders::with('user','plan','getway')->findOrFail($id);
        $pdf = PDF::loadView('admin.user.invoice', compact('invoice'));
        return $pdf->download('invoice.pdf');
    }


    public function sendmail($id, Request $request){
        abort_if(!Auth()->user()->can('user.mail'), 401);
        $user = User::findorFail($id);
        $data   = [
            'email' =>  $user->email,
            'subject' => $request->subject,
            'msg'     => $request->msg,
            'type'    => 'usermail',
        ];
        // Send Mail
        if(env('QUEUE_MAIL') == 'on'){
            dispatch(new SendEmailJob($data));
        }else{
            Mail::to($user->email)->send(new UserMail($data));
        }

        return response()->json('Email Sent Successfully!');
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
        abort_if(Auth::user()->role_id != 1, 403);
        // Validate
        $request->validate([
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email,'.$id,
            'avatar'       => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        

        // User store
        $user_store        = User::findOrFail($id);
        $user_store->name  = $request->name;
        $user_store->email = $request->email;
        
        if($request->password != ""){
            $user_store->password = Hash::make($request->password);
        }
        $user_store->status   = $request->status;
        $user_store->save();

        return response()->json('User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success','User deleted successfully!');
    }


    function storageSize($user_id, $project_id){
        $dir = 'uploads/' . $user_id .'/' .$project_id . '/';
        return (float) $this->folderSize($dir);
    }

    function folderSize($dir){
		$file_size = 0;
		if (!file_exists($dir)) {
		return $file_size;
		}
		
		foreach(\File::allFiles($dir) as $file)
		{
		    $file_size += $file->getSize();
		}
		return $file_size = str_replace(',', '', number_format($file_size / 1048576,2));
	}

}
