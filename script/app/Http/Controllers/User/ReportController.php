<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Column;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Projectactivity;
use App\Models\Projectscreenshot;
use App\Models\Projectuser;
use App\Models\Screenshot;
use App\Models\Task;
use App\Models\Taskscreenshot;
use App\Models\Taskuser;
use App\Models\Team;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(){
        $q = request()->has('q') ? request()->get('q') : 'all';
        $projects = Project::where(function($query) use ($q){
            if ($q != 'all') { $query->where('status', $q); }
        })
        ->with('projectuser')
        ->withCount('column')
        ->withCount('completed_column')
        ->where(function($query){
            $query->where('user_id', Auth::user()->id)
            ->orWhereHas('projectuser', function($query){
            $query->where('user_id', Auth::user()->id)->where('is_admin', 1);
            })
            ->with('projectuser');
        })->paginate(20);

        $count['all'] = Project::where('user_id',Auth::id())->count();
        $count['pending'] = Project::where([['status','2'],['user_id', Auth::id()]])->count();
        $count['completed'] = Project::where([['status','1'],['user_id', Auth::id()]])->count();
        return view('user.reports.index', compact('projects','q', 'count'));
    }

    public function search(Request $request)
    {
        $type = $request->type;
        $q = '';
        $s = [];
        
        if ($type == 'date') {
            $range = explode(' - ', $request->q);
            $s['from'] = Carbon::parse($range[0]);
            $s['to'] = Carbon::parse($range[1]);
        }
        if($type == 'project'){
            $s['name'] = $request->q;
        }


       $projects = Project::with('projectuser')
        ->where(function($query) use ($s, $type){
            if($type == 'date'){
                if ($s['from'] == $s['to']) {
                    $query->where('created_at','>=',$s['from']);
                } else{
                    $query->whereBetween('created_at',[$s['from'], $s['to']]);
                }
                
            }elseif($type == 'project'){
                $query->where('name','LIKE', "%".$s['name']."%"); 
            }
        })
        ->withCount('column')
        ->withCount('completed_column')
        ->where(function($query){
            $query->where('user_id', Auth::user()->id)
            ->orWhereHas('projectuser', function($query){
            $query->where('user_id', Auth::user()->id)->where('is_admin', 1);
            });
        })->paginate(20);
        $count['all'] = Project::where('user_id',Auth::id())->count();
        $count['pending'] = Project::where([['status','2'],['user_id',Auth::id()]])->count();
        $count['completed'] = Project::where([['status','1'],['user_id',Auth::id()]])->count();
      
        return view('user.reports.index', compact('projects','q','count'));
    }

    public function show($id){
        $project = Project::with('projectuser')
        ->where(function($query){
            $query->where('user_id', Auth::user()->id)
            ->orWhereHas('projectuser', function($query){
            $query->where('user_id', Auth::user()->id)->where('is_admin', 1);
            })
            ->with('projectuser');
        })->find($id);
        
        abort_if(!$project, 403, 'You don\'t have permission to access!');
        return view('user.reports.show', compact('project'));
    }

    public function stats(Request $request){
        $project_id = $request->id;
        $project = Project::findOrfail($project_id);
        $users = Projectuser::where('project_id',$project_id)->with('user')->get();
        $columns = Column::where('project_id', $project_id)->with('task')->withCount('task','pending_task','completed_task')->get();

        $times = Time::where('project_id', $project_id)->with('user')->selectRaw('user_id, sum(total_time) as time, sum(price) as price')->groupBy('user_id')->get();

        $dir = 'uploads/' . $project->user_id .'/' .$project->id . '/';
        $storage = $this->folderSize($dir);
        
        $fileCount = $this->totalFileCount($project_id);

        $data = [
            'columns' => $columns, 
            'user' => $users,
            'user_stats' => $times,
            'storage' => $storage,
            'files' => $fileCount
        ];

        return $data;
    }

    public function columnReport($id){
        $column = Column::where('id', $id)->with('task')->first();
        return view('user.reports.column', compact('column'));
    }

    public function attachments(Request $request){
        $id = $request->id;
        $column = Column::where('id', $id)->whereHas('comment_with_attachment')->first();
        return $column->comment_with_attachment ?? null;
    }

    
    public function attachmentList($id){
        $column = Attachment::where('project_id',$id)->with('comment')->get();
        $task_screenshot = Taskscreenshot::where('project_id', $id)->with('screenshot','user')->get();
        $project_screenshot = Projectscreenshot::where('project_id', $id)->with('screenshot', 'user')->get();
       
        foreach ($column as $value) {
            $data[] = [
                'attachment' => $value->file_name,
                'date' => $value->comment->created_at->isoFormat('LLL'),
                'user' => $value->comment->user->name
            ];    
        }

        foreach ($task_screenshot as $key => $value) {
            $data[] = [
                'attachment' => $value->screenshot->file,
                'date' => $value->screenshot->created_at->isoFormat('LLL'),
                'user' => $value->user->name
            ];
        }

        foreach ($project_screenshot as $key => $value) {
            $data[] = [
                'attachment' => $value->screenshot->file,
                'date' => $value->screenshot->created_at->isoFormat('LLL'),
                'user' => $value->user->name
            ];
        }

        $data = json_decode(json_encode($data));
        return view('user.reports.attachments', compact('data'));

    }

    public function userReport($id, $project_id){
        $user = User::findOrFail($id)->name;
        $project = Project::findOrFail($project_id);
        $gps = Projectactivity::select('project_id','user_id','latitude','longitude')->where('user_id', $id)->where('project_id', $project_id)->groupBy('latitude','longitude')->get() ?? Activity::select('project_id','user_id','latitude','longitude')->where('user_id', $id)->where('project_id', $project_id)->groupBy('latitude','longitude')->get();
        return view('user.reports.user', compact('project_id','id','user','project','gps'));
    }

    public function getGps(Request $request){
        $id = $request->project_id;
        $project_id = $request->user_id;
        return Projectactivity::select('project_id','user_id','latitude','longitude')->where('user_id', $id)->where('project_id', $project_id)->groupBy('latitude','longitude')->get() ?? Activity::select('project_id','user_id','latitude','longitude')->where('user_id', $id)->where('project_id', $project_id)->groupBy('latitude','longitude')->get();
        
    }

    public function deleteScreenshot(Request $request){
        $ids = $request->ids;
        $project =  Project::findOrFail($request->project_id);
        if (Auth::id() !== $project->user_id) {
            return 404;
        }
        $screenshot = Screenshot::whereIn('id',$ids);
        foreach ($screenshot->get() as $value) {
            $file = $value->file;
            if (\File::exists($file)) {
                unlink($file);
            }
        }
        $screenshot->delete();
        return response()->json('Successfully Deleted!');
    }

    public function userStats(Request $request){
        $user_id = $request->id;
        $project_id = $request->project_id;
        $project = Project::findOrfail($project_id);
        $data['completed'] = $data['pending'] = 0;
        $total_task = Taskuser::where('user_id', $user_id)->where('project_id', $project_id)->withCount(['pending_task','completed_task','task'])->get();

        $data['project_screenshots'] = Projectscreenshot::with('screenshot')->where([['user_id', $user_id],['project_id', $project_id]])->orderBy('screenshot_id','DESC')->paginate(15);
        $data['task_screenshots'] = Taskscreenshot::with('screenshot')->where('user_id', $user_id)->where('project_id', $project_id)->orderBy('screenshot_id','DESC')->paginate(15);

        $data['seconds'] = Time::where('user_id', $user_id)->where('project_id', $project_id)->orderBy('started_at', 'asc')->selectRaw('DATE_FORMAT(started_at, "%d-%m-%Y") date, sum(total_time) totaltime')->groupBy('date')->get();
        
        foreach ($total_task as  $value) {
           $data['completed'] += $value->completed_task_count;
           $data['pending'] += $value->pending_task_count;
        }
        $data['has_access'] = $project->user_id == Auth::id();
        $data['total'] = count($total_task);
        return $data;
    }


    public function rendertask(Request $request){
        $project_id = $request->project_id;
        $tasks = Taskuser::with(['user','task','project'])->whereHas('project', function($q) use ($project_id){
            $q->where('project_id', $project_id);
        })->get();
        return $tasks;
    }

    public function store(){
        
    }

    public function edit(){
        
    }

    public function update(){
        
    }

    public function delete(){
        
    }

    public function summary(){
        return view('user.reports.summary');
    }

    public function detailed(){
        return view('user.reports.detailed');
    }

    public function weekly(){
        return view('user.reports.weekly');
    }

    public function shared(){
        return view('user.reports.shared');
    }

    function fileCount($dir){
        $file_size = 0;
		if (!file_exists($dir)) {
		return $file_size;
		}
        return count(\File::allFiles($dir));
    }

    function folderSize($dir){
		$file_size = 0;
		if (!file_exists($dir)) {
		return $file_size;
		}
		
		foreach(\File::allFiles($dir) as $file)
		{
		    $file_size += $file->getSize();
		}
		return $file_size = str_replace(',', '', number_format($file_size / 1048576,2));
	}

    function fileSize($file){
        return $file->getSize();
    }


    function totalFileCount($id){
        $column = Attachment::where('project_id',$id)->with('comment')->get();
        $task_screenshot = Taskscreenshot::where('project_id', $id)->with('screenshot','user')->get();
        $project_screenshot = Projectscreenshot::where('project_id', $id)->with('screenshot', 'user')->get();

        $count = 0;
        foreach ($column as $value) {
            $count++;
            $data[] = [
                'attachment' => $value->file_name,
                'user' => $value->comment->user->name
            ];    
        }

        foreach ($task_screenshot as $key => $value) {
            $count++;
            $data[] = [
                'attachment' => $value->screenshot->file,
                'user' => $value->user->name
            ];
        }

        foreach ($project_screenshot as $key => $value) {
            $count++;
            $data[] = [
                'attachment' => $value->screenshot->file,
                'user' => $value->user->name
            ];
        }

        return $count;   
    }
}
