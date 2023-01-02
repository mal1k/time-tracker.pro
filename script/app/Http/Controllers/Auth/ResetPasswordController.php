<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
   // protected $redirectTo = RouteServiceProvider::HOME;


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
         
        } 
        elseif(Auth::check()) {
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
}
