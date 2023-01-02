<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\Team;
use App\Models\User;
use App\Models\Userplan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function register_form(Request $request)
    {
        return view('auth.register',compact('request'));
    }

    public function registerFromInvite(Request $request){
        $token = $request->token;
        $decrypted = Crypt::decryptString($token);
        $creator_id = $hourly_rate = '';
        if ($decrypted) {
            $arr = explode(",", $decrypted);
            $creator_id = $arr[0];
            $hourly_rate = $arr[1];
        }    
        return view('auth.invite_register', compact('creator_id','hourly_rate'));
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $plan = Plan::where('is_default', 1)->first();

        $userplan = new Userplan;
        $userplan->user_id = $user->id;
        $userplan->name = $plan->name ?? 'free';
        $userplan->storage_size = $plan->storage_size ?? 0;
        $userplan->project_limit = $plan->project_limit ?? 0;
        $userplan->user_limit = $plan->user_limit ?? 0;
        $userplan->group_limit = $plan->group_limit ?? 0;
        $userplan->gps = $plan->gps ?? 0;
        $userplan->mail_activity = $plan->mail_activity ?? 0;
        $userplan->adminable_project = $plan->adminable_project ?? 0;
        $userplan->screenshot = $plan->screenshot ?? 0;
        $userplan->is_featured = $plan->is_featured ?? 0;
        $userplan->save();
   
        if ($request->ci) {
            $team = new Team;
            $team->user_id = $user->id;
            $team->creator_id = Crypt::decrypt($request->ci);
            $team->status = 1;
            $team->h_rate = Crypt::decrypt($request->hr);
            $team->save();
        }

        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    public function plan_register(Request $request){
        $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $plan = Plan::findOrFail($request->planid);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $trial = $plan->is_trial;

        if($trial == 0){
            $plan = Plan::where('is_default', 1)->first();
        }

        $userplan = new Userplan;
        $userplan->user_id = $user->id;
        $userplan->name = $plan->name ?? 'free';
        $userplan->storage_size = $plan->storage_size ?? 0;
        $userplan->project_limit = $plan->project_limit ?? 0;
        $userplan->user_limit = $plan->user_limit ?? 0;
        $userplan->group_limit = $plan->group_limit ?? 0;
        $userplan->gps = $plan->gps ?? 0;
        $userplan->mail_activity = $plan->mail_activity ?? 0;
        $userplan->adminable_project = $plan->adminable_project ?? 0;
        $userplan->screenshot = $plan->screenshot ?? 0;
        $userplan->is_featured = $plan->is_featured ?? 0;
        $userplan->save();

        $tax = Option::where('key','tax')->first();
        $prefix = Option::where('key','invoice_prefix')->first();
        $auto_enrollment = Option::where('key','auto_enroll_after_payment')->pluck('value')->first();

        if ($trial == 1 && $plan) {
            $order = new Orders;
            $order->plan_id = $plan->id;
            $order->getway_id = 8;
            $order->user_id = $user->id;
            $order->tax = 0;
            $order->amount = 0;    
            $order->payment_id = Str::random(10);  
            $order->is_trial = $plan->is_trial;
            $order->status = $auto_enrollment == 'on' ? 1 : 2;
            $order->will_expire = Carbon::now()->addDays($plan->duration);
            $order->save();

            $order = Orders::findOrFail($order->id);
            $order->invoice_id = $prefix->value . $order->id;
            $order->save();
        }

        Auth::login($user);

        if ($trial == 0 && $request->planid) {
            return redirect()->route('user.plan.gateways', $request->planid);
        }

        Session::flash('message', 'Transaction Successfully done! & Plan Activated');
        Session::flash('type', 'success');
        return redirect()->route('user.plan.subscribe');
    }

    
    public function logout(Request $request) {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }

}
