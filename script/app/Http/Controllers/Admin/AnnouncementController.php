<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!Auth()->user()->can('announcement.index'), 401);
        $announcement = Term::with('announcement')->where('type', 'announcement')->paginate(10);
        return view('admin.announcement.index', compact('announcement'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!Auth()->user()->can('announcement.create'), 401);
        return view('admin.announcement.create');
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
            'title'        => 'required',
            'description' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Term Data Store
            $announcement           = new Term();
            $announcement->title    = $request->title;
            $announcement->slug     = Str::slug($request->title, '-');
            $announcement->type     = 'announcement';
            $announcement->status   = $request->status;
            $announcement->featured = 1;
            $announcement->save();

            // Term Meta For description
            $announcement_meta          = new Termmeta();
            $announcement_meta->term_id = $announcement->id;
            $announcement_meta->key     = 'announcement_desc';
            $announcement_meta->value   = $request->description;
            $announcement_meta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }


        return response()->json('Announcement Added Successfully');
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
        abort_if(!Auth()->user()->can('announcement.edit'), 401);
        $announcement = Term::with('announcement')->findOrFail($id);
        return view('admin.announcement.edit', compact('announcement'));
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
            'title'        => 'required|unique:terms,title,' . $id,
            'description' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Term Data Store
            $announcement           = Term::findOrFail($id);
            $announcement->title    = $request->title;
            $announcement->slug     = Str::slug($request->title, '-');
            $announcement->type     = 'announcement';
            $announcement->status   = $request->status;
            $announcement->featured = 1;
            $announcement->save();

            // Term Meta For description
            $announcement_meta          = new Termmeta();
            $announcement_meta->term_id = $announcement->id;
            $announcement_meta->key     = 'announcement_desc';
            $announcement_meta->value   = $request->description;
            $announcement_meta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }

        return response()->json('Announcement Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(!Auth()->user()->can('announcement.delete'), 401);
        $delete = Term::findOrFail($id);
        $delete->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}
