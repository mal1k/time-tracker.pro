<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Orders;
use App\Models\Plan;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Userplan;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectTo()
    {
        if(Auth::check() && Auth::user()->role_id == 1) {
            return $this->redirectTo = route('admin.dashboard');
        } elseif(Auth::check()) {
            if(Auth::user()->two_step_auth == 1) {
               return $this->redirectTo = route('user.otp');
            } else {
                // if (Session::has('plan')) {
                //     return $this->redirectTo = route('user.plan.subscribe');
                // }
                return $this->redirectTo = route('user.dashboard');
            }
        }
        else {
            return $this->redirectTo = route('login');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {         
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

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
        
        return $user;
    }
}
