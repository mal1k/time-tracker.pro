<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutSectionController extends Controller
{
    public function index()
    {
        $about = Term::where('type', 'aboutsection')->first();
        return view('admin.about.edit', compact('about'));
    }

    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'short_title' => 'required',
            'status'     => 'required',
            'title'     => 'required',
            'short_description' => 'required',
            'page_content'     => 'required',
            'button_status'     => 'required',
            'image'=>'image|max:300'
        ]);

        $page_logo_path = $page_logo_name = '';
        // Image Check
        if ($request->hasFile('image')) {
            $page_logo      = $request->file('image');
            $page_logo_name = hexdec(uniqid()) . '.' . $page_logo->getClientOriginalExtension();
            $page_logo_path = 'uploads/' . date('y/m/');
            $page_logo->move($page_logo_path, $page_logo_name);
        }


        // Feature Data Store
        $about = Term::where('type', 'aboutsection')->first();
        if(empty($about)){
            $about  = new Term();
        }
        $about->title    = $request->title;
        $about->type     = 'aboutsection';
        $about->slug = '';
        $about->status   = $request->status;
        $about->featured = 1;
        $about->save();

        $about = Term::findOrFail($about->id);
        $about->slug = Str::slug($request->title). $about->id;
        $about->save();
        
        

        // Page Meta data store
        $aboutmeta          = Termmeta::where('term_id',$about->id)->first();
        $info = !empty($aboutmeta) ? json_decode($aboutmeta->value) : ''; 
        if(empty($aboutmeta)){
            $aboutmeta  = new Termmeta();
        }

        
        // Data
        $data = [
            'short_description' => $request->short_description,
            'short_title' => $request->short_title,
            'page_content' => $request->page_content,
            'button_status' => $request->button_status,
            'button_text' => $request->button_text,
            'image' => $page_logo_path != '' ? $page_logo_path. $page_logo_name : (!empty($info->image) ? $info->image : '')
        ];


        $aboutmeta->term_id = $about->id;
        $aboutmeta->key     = 'about_meta';
        $aboutmeta->value   = json_encode($data);
        $aboutmeta->save();

        return response()->json('About Section Updated Successfully');
    }


}
