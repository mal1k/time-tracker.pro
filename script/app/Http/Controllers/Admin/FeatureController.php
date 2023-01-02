<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!Auth()->user()->can('web.feature'), 401);
        $features = Term::where('type', 'feature')->paginate(20);
        return view('admin.feature.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!Auth()->user()->can('web.feature'), 401);
        return view('admin.feature.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'color' => 'required',
            'status'     => 'required',
            'title'     => 'required',
            'short_description' => 'required',
            'icon'     => 'required|image|max:100',
        ]);

        $page_logo_path = $page_logo_name = '';
        // Image Check
        if ($request->hasFile('icon')) {
            $page_logo      = $request->file('icon');
            $page_logo_name = hexdec(uniqid()) . '.' . $page_logo->getClientOriginalExtension();
            $page_logo_path = 'uploads/features/' . date('y/m/');
            $page_logo->move($page_logo_path, $page_logo_name);
        }

        // Data
        $data = [
            'short_description' => $request->short_description,
            'page_content' => $request->page_content ?? '',
            'color' => $request->color,
            'icon' => $page_logo_path . $page_logo_name 
        ];


        DB::beginTransaction();
        try {
            // Feature Data Store
            $feature           = new Term();
            $feature->title    = $request->title;
            $feature->type     = 'feature';
            $feature->slug = '';
            $feature->status   = $request->status;
            $feature->featured = 1;
            $feature->save();
    
            $feature = Term::findOrFail($feature->id);
            $feature->slug = Str::slug($request->title). $feature->id;
            $feature->save();
    
            // Page Meta data store
            $feature_meta          = new Termmeta();
            $feature_meta->term_id = $feature->id;
            $feature_meta->key     = 'feature_meta';
            $feature_meta->value   = json_encode($data);
            $feature_meta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        return response()->json('Feature Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(!Auth()->user()->can('web.feature'), 401);
        $feature = Term::findOrFail($id);
        return view('admin.feature.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate
        $request->validate([
            'color' => 'required',
            'status'     => 'required',
            'title'     => 'required',
            'short_description' => 'required',
            'icon'     => 'nullable|image|max:200',
        ]);

        $page_logo_path  = $page_logo_name = '';
        // Image Check
        if ($request->hasFile('icon')) {
            $page_logo      = $request->file('icon');
            $page_logo_name = hexdec(uniqid()) . '.' . $page_logo->getClientOriginalExtension();
            $page_logo_path = 'uploads/features/' . date('y/m/');
            $page_logo->move($page_logo_path, $page_logo_name);
        }

        DB::beginTransaction();
        try {
            // Feature Data Store
            $feature           = Term::findOrFail($id);
            $feature->title    = $request->title;
            $feature->slug     = Str::slug($request->title) . $id;
            $feature->type     = 'feature';
            $feature->status   = $request->status;
            $feature->featured = 1;
            $feature->save();
    
            // Feature Meta data store
            $feature_meta          = Termmeta::where('term_id', $feature->id)->first();
            $info = json_decode($feature_meta->value);
            // Data
            $data = [
                'short_description' => $request->short_description,
                'page_content' => $request->page_content ?? '',
                'color' => $request->color,
                'icon' => $page_logo_path != '' ? $page_logo_path. $page_logo_name : $info->icon
            ];
    
    
            $feature_meta->key     = 'feature_meta';
            $feature_meta->value   = json_encode($data);
            $feature_meta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        return response()->json('Feature Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(!Auth()->user()->can('web.feature'), 401);
        $delete = Term::findOrFail($id);
        $delete->delete();
        return redirect()->back()->with('message', 'Successfully Deleted'); 
    }
}
