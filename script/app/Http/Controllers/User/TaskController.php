<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Taskuser;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team = Team::where([['user_id', Auth::id()],['status', 2]])->first();
        if($team){
            $team->status = 1;
            $team->save();
        }
        $projects = Project::with('projectuser')->where(function($query){
            $query->where('user_id', Auth::user()->id)
            ->orWhereHas('projectuser', function($query){
            $query->where('user_id', Auth::user()->id);
            })->with('projectuser');
        })->where('status',2)->get();
        return view('user.task.index',compact('projects'));
    }

    public function loadTasks(Request $request){
        $filter = $request->filter;
        $type = $request->type;
        $request_type = $request->request_type;
        if ($type == "status") {
            if ($filter == "all") {
                if ($request_type != "") {
                    $tasks[$request_type] = $this->filter_task($request_type);
                }else{
                    $tasks['today'] = $this->filter_task('today');
                    $tasks['upcoming'] = $this->filter_task('upcoming');
                    $tasks['overdue'] = $this->filter_task('overdue');
                    $tasks['no_overdue'] = $this->filter_task('no_overdue');
                    $tasks['completed'] = $this->filter_task('completed');
                }
            }elseif ($filter != "") {
                $tasks[$filter] = $this->filter_task($filter);
            }else{
                $tasks['today'] = $this->filter_task('today');
            }
        }elseif($type == "project"){
            $tasks['project'] = $this->filter_task('project', $filter); //type and id
        }else{
            $tasks['today'] = $this->filter_task('today');
        }
        return $tasks;
    }

    function filter_task($filter, $id=''){
        if ($filter == "project" && $id != "") {
            $tasks = Taskuser::with(['user','task','project'])->where('user_id', Auth::id())->where('project_id', $id)->paginate(20);
        }else{
            $tasks = Taskuser::with(['user','task','project'])->where('user_id', Auth::id())
            ->whereHas('project', function($q){
                $q->where('status', 2);
            })
            ->whereHas('task', function($q) use($filter){ 
                if ($filter == 'upcoming') {
                    $q->where('due_date', '>', Carbon::today());
                }elseif ($filter== 'today') {
                    $q->where('due_date', Carbon::today());
                }elseif ($filter == 'overdue') {
                    $q->where('due_date', '<', Carbon::today());
                }elseif ($filter == 'no_overdue'){
                    $q->where('due_date',null);
                }elseif ($filter == 'completed') {
                    $q->where('status', 1);
                } 
            })->paginate(20);
        }
        return $tasks;
    }


    public function taskModalData(Request $request){
        $data['task'] = $task = Task::findOrFail($request->id);
        $data['comment'] = Comment::with(['user','attachment'])->where('task_id', $request->id)->latest()->get();
        return json_encode($data);
    }

    public function addCommentOnTask(Request $request){
        $request->validate([
            'comment' => 'required|max:100',
        ]);
        
        $attachment_path = $attachment_name = "";

        // If attachment found
        if ($request->hasFile('attachment')) {
            $attachment      = $request->file('attachment');
            $attachment_name = hexdec(uniqid()) . '.png';
            $attachment_path = 'uploads/' . date('y/m/');
            $attachment->move($attachment_path, $attachment_name);
        }
        
        $comment = new Comment;   
        $comment->user_id = $request->user_id;
        $comment->task_id = $request->task_id;
        $comment->comment = $request->comment;
        $comment->save();

        if ($attachment_path != '') {
            $attachment = new Attachment;
            $attachment->comment_id = $comment->id;
            $attachment->project_id = $request->project_id;
            $attachment->file_name = $attachment_path . $attachment_name;
            $attachment->save();
        }
    

        $data = [
            'file' => $attachment->file_name ?? '',
            'comment' =>  $request->comment,
            'name' =>  Auth::user()->name,
            'created_at' => $comment->created_at
        ];

        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
