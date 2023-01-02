<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnalyticSectionController extends Controller
{
    public function index()
    {
        abort_if(!Auth()->user()->can('web.analytic'), 401);
        $analytic = Term::where('type', 'analytic')->first();
        return view('admin.analytic.edit', compact('analytic'));
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
            'image'=>'image|max:500'
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
        $analytic = Term::where('type', 'analytic')->first();
        if (empty($analytic)) {
            $analytic  = new Term();
        }
        DB::beginTransaction();
        try {
            $analytic->title    = $request->title;
            $analytic->type     = 'analytic';
            $analytic->slug = '';
            $analytic->status   = $request->status;
            $analytic->featured = 1;
            $analytic->save();

            $analytic = Term::findOrFail($analytic->id);
            $analytic->slug = Str::slug($request->title) . $analytic->id;
            $analytic->save();

            $analyticmeta          = Termmeta::where('term_id', $analytic->id)->first();
            $info = !empty($analyticmeta) ? json_decode($analyticmeta->value) : '';
            if (empty($analyticmeta)) {
                $analyticmeta  = new Termmeta();
            }
            // Data
            $data = [
                'short_description' => $request->short_description,
                'short_title' => $request->short_title,
                'page_content' => $request->page_content,
                'button_status' => $request->button_status,
                'button_text' => $request->button_text,
                'image' => $page_logo_path != '' ? $page_logo_path . $page_logo_name : (!empty($info->image) ? $info->image : '')
            ];


            $analyticmeta->term_id = $analytic->id;
            $analyticmeta->key     = 'analytic_meta';
            $analyticmeta->value   = json_encode($data);
            $analyticmeta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }




        // Page Meta data store


        return response()->json('Analytic Section Updated Successfully');
    }
}
