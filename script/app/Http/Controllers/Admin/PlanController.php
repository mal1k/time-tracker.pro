<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!Auth()->user()->can('plan.index'), 401);
        $plans = Plan::withCount('orders')->withSum('orders','amount')->paginate(10);
        return view('admin.plan.index', compact('plans'))->with('i', 1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!Auth()->user()->can('plan.create'), 401);
        return view('admin.plan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required|unique:plans',
            'duration' => 'required|numeric',
            'price' => 'required|numeric|between:0,9999999.99',
            'storage_size' => 'required|numeric|between:0,9999999.99',
            'gps' => 'required',
            'user_limit' => 'required',
            'project_limit' => 'required',
            'group_limit' => 'required',
            'screenshot' => 'required',
       ]);
        

       DB::beginTransaction();
       try {
           $plan = new Plan();
           $plan->name = $request->name;
           $plan->duration = $request->duration;
           $plan->price = $request->price;
           $plan->storage_size = $request->storage_size;
           $plan->gps = $request->gps;
           $plan->user_limit = $request->user_limit;
           $plan->group_limit = $request->group_limit;
           $plan->screenshot = $request->screenshot;
           $plan->project_limit = $request->project_limit;
           $plan->is_featured = $request->is_featured;
           $plan->adminable_project = $request->adminable_project;
           $plan->mail_activity = $request->mail_activity; 
           $plan->is_trial = $request->is_trial;
           $plan->status = $request->status;
           $plan->is_default = $request->is_default;
           $plan->save();
           DB::commit();
       } catch (\Throwable $th) {
           DB::rollBack();
       }

       return response()->json('Plan Created Successfully!');
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
        abort_if(!Auth()->user()->can('plan.edit'), 401);
        $plan = Plan::findOrFail($id);
        return view('admin.plan.edit', compact('plan'));
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
        $request->validate([
            'name' => 'required|unique:plans,name,'.$id,
            'duration' => 'required|numeric',
            'price' => 'required|numeric|between:0,9999999.99',
            'storage_size' => 'required|numeric|between:0,9999999.99',
          
            'gps' => 'required',
            'user_limit' => 'required',
            'project_limit' => 'required',
            'group_limit' => 'required',
            'screenshot' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $plan = Plan::findOrFail($id);
            $plan->name = $request->name;
            $plan->duration = $request->duration;
            $plan->price = $request->price;
            $plan->storage_size = $request->storage_size;
          
            $plan->gps = $request->gps;
            $plan->user_limit = $request->user_limit;
            $plan->group_limit = $request->group_limit;
            $plan->screenshot = $request->screenshot;
            $plan->project_limit = $request->project_limit;
            $plan->status = $request->status;
            $plan->adminable_project = $request->adminable_project;
            $plan->mail_activity = $request->mail_activity; 
            $plan->is_trial = $request->is_trial;
            $plan->status = $request->status;
            $plan->is_default = $request->is_default;
            $plan->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        return response()->json('Plan Updated Successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(!Auth()->user()->can('plan.delete'), 401);
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return redirect()->back()->with('message', 'Successfully Deleted!');   
    }
}
