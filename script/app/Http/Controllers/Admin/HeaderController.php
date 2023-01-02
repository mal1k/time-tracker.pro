<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
class HeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!Auth()->user()->can('web.header'), 401);
        $header=Option::where('key','header')->first();
        $header=json_decode($header->value) ?? '';
        return view('admin.header.show',compact('header'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
        'title' => 'required|max:100',
        'short_title' => 'required|max:100',
        'file' => 'image|max:1000',
       ]);

       $header=Option::where('key','header')->first();
       if(empty($header)){
        $header=new Option;
        $header->key='header';
       }

       $data['title']=$request->title;
       $data['short_title']=$request->short_title;
       $data['youtube_link']=$request->youtube_link;
       $data['get_start_form']=$request->get_start_form;
       $header->value=json_encode($data);
       $header->save();

     
       if ($request->hasFile('file')) {
        $thum_image      = $request->file('file');
        $thum_image_name = 'header.png';
        $thum_image_path = 'uploads/';
        $thum_image->move($thum_image_path, $thum_image_name);
       }

       return response()->json('section updated');
    

    }

    
}
