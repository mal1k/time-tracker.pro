<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        if(Auth::check() && Auth::user()->role_id == 1) {

            if(Auth::user()->two_step_auth == 1) {
                return $this->redirectTo = route('profile.otp');
            } 
            return $this->redirectTo = route('admin.dashboard');
         
        }elseif(Auth::check() && Auth::user()->role_id == 2) {
            if(Auth::user()->two_step_auth == 1) {
               return $this->redirectTo = route('user.otp');
            } else {
                return $this->redirectTo = route('user.dashboard');
            }
        }
        else {
            return $this->redirectTo = route('login');
        }
    }

    public function logout(Request $request) {
        Auth::logout();
        Session::flush();
        return redirect('/admin/login');
    }
}
