<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(){
        return view('user.settings.index');
    }

    public function alerts(){
        return view('user.settings.alerts');
    }

    public function accounts(){
        return view('user.settings.accounts');
    }

    public function authentication(){
        return view('user.settings.authentication');
    }

    public function customfields(){
        return view('user.settings.customfields');
    }

    public function import(){
        return view('user.settings.import');
    }
}
