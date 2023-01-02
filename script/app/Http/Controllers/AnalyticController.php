<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function index()
    {
         $analytic=Term::where('type','analytic')->with('analyticmeta')->where('status',1)->first();

         return view('analytic.show',compact('analytic'));
    }
}
