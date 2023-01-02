<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\Project;
use App\Models\Projectuser;
use App\Models\Taskuser;
use App\Models\Team;
use App\Models\Time;
use App\Models\Userplan;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        return view('user.dashboard');
    }

    public function userDetails(Request $request){
        $data['total_time'] = Time::where('user_id', Auth::id())->sum('total_time');
        $data['total_task'] = Taskuser::where('user_id', Auth::id())->count();
        $data['completed_task'] = Taskuser::where('user_id', Auth::id())->with('completed_task')->whereHas('completed_task')->count();
        $data['pending_task'] = Taskuser::where('user_id', Auth::id())->with('pending_task')->whereHas('pending_task')->count();
        $data['total_earning'] = Time::where('user_id', Auth::id())->sum('price');
       
        $data['completed_project'] = Projectuser::with('completed_project')->where('user_id', Auth::id())->whereHas('completed_project')->count();

        $start = $request->start ? Carbon::parse($request->start) : "";
        $end = $request->end ? Carbon::parse($request->end) : "";
        
        $data['running_project'] = $start && $end ? Projectuser::with('running_project')->where('user_id', Auth::id())->whereBetween('created_at', [$start, $end])->whereHas('running_project')->latest()->paginate(10) : Projectuser::with('running_project')->where('user_id', Auth::id())->whereHas('running_project')->latest()->paginate(10);
        $data['user_plan'] = Userplan::where('user_id',Auth::id())->first();
        $data['user_limit'] = Team::where('creator_id',Auth::id())->count();
        $data['group_limit'] = Group::where('user_id',Auth::id())->count();
        $data['project_limit'] = Project::where('user_id',Auth::id())->count();
        $data['expired_at'] = Orders::where([['user_id',Auth::id()],['status', 1]])->pluck('will_expire')->first();
        $data['storage']=number_format(folderSize('uploads/'.Auth::id()),2).'/'.$data['user_plan']->storage_size;


        return $data;
    }

    public function recentChartStats(){
        $data = Time::with('project')
        ->selectRaw('project_id, sum(total_time) as time')
        ->where([['user_id', Auth::id()],['started_at', '>=', Carbon::now()->subWeek()]])
        ->groupBy('project_id')
        ->get();
        
        foreach ($data as $key => $value) {
            $result[] = [
                'time' => $value->time,
                'project' => $value->project->name,
            ];
        }
        return $result ?? [];

    }

    public function getNotification(){
        $project = Projectuser::with('project')->whereHas('project')->where([['user_id', Auth::id()],['seen', 0]])->get();
        $task = Taskuser::with('task')->whereHas('task')->where([['user_id', Auth::id()],['seen', 0]])->get();
        $allprojects = [];
        $alltasks = [];
        foreach($project as $i => $item){
            $allprojects[$i]['name'] = $item->project->name;
            $allprojects[$i]['id'] = $item->project->id;
            $allprojects[$i]['type'] = 'project';
            $allprojects[$i]['time'] = $item->project->created_at->diffForHumans();
        }
        foreach($task as $k => $item){
            $alltasks[$k]['name'] = $item->task->name;
            $alltasks[$k]['id'] = $item->task->id;
            $alltasks[$k]['type'] = 'task';
            $alltasks[$k]['project_id'] = $item->project_id;
            $alltasks[$k]['time'] = $item->task->created_at->diffForHumans();
        }
        return collect(array_merge($allprojects,$alltasks))->sortby('time');
    }
}
