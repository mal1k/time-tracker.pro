<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class LanguageController extends Controller
{
    public function set(Request $request)
    {
        if($request->lang)
        {
            Session::put('locale',$request->lang);

            return response()->json('success');
        }else{
            return response()->json('error');
        }
    }
}
