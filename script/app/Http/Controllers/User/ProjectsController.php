<?php


namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\AssignNewProjectMail;
use App\Mail\AssignNewTaskMail;
use App\Mail\TaskCompleteMail;
use App\Models\Attachment;
use App\Models\Column;
use App\Models\Comment;
use App\Models\Team;
use App\Models\Project;
use App\Models\Projectuser;
use App\Models\Group;
use App\Models\Groupuser;
use App\Models\Projectgroup;
use App\Models\Task;
use App\Models\Taskuser;
use App\Models\User;
use App\Models\Userplan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProjectsController extends Controller
{
    public function index()
    {
        $team = Team::where([['user_id', Auth::id()], ['status', 2]])->first();
        if ($team) {
            $team->status = 1;
            $team->save();
        }
        $users = Team::where('creator_id', Auth::id())->with('user')->get();
        $groups = Group::where('user_id', Auth::id())->where('status', 1)->get();
        $plan = Userplan::where('user_id', Auth::id())->first();
        $projects = Project::with('projectuser')
            ->withCount('column')
            ->withCount('completed_column')
            ->where(
                function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->orWhereHas('projectuser', function ($query) {
                            $query->where('user_id', Auth::user()->id);
                        })->with('projectuser');
                }
            )->latest()->paginate(15);

        $percent = $this->storageUsed(Auth::id());
        $project_limit = getplandata('project_limit');
        $total_project = Project::where('user_id', Auth::id())->count();

        return view('user.project.index', compact('users', 'groups', 'projects', 'plan', 'percent', 'project_limit', 'total_project'));
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $type = $request->type;

        $users = Team::where('creator_id', Auth::id())->with('user');
        $groups = Group::where('user_id', Auth::id())->where('status', 1)->get();
        $plan = Userplan::where('user_id', Auth::id())->first();
        $percent = $this->storageUsed(Auth::id());
        $project_limit = getplandata('project_limit');
        $total_project = Project::where('user_id', Auth::id())->count();

        $projects = Project::with('projectuser')
            ->withCount('column')
            ->withCount('completed_column')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereHas('projectuser', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    })->with('projectuser');
            });

        if ($type == 'name') {
            $projects = $projects->where('name', 'LIKE', "%$q%")->latest()->paginate(15);
            $users = $users->get();
        } elseif ($type == 'email') {
            $projects = $projects->latest()->paginate(15);

            $users = $users->whereHas('user', function ($query) use ($q) {
                $query->where('email', 'LIKE', "%$q%");
            })->get();
        }

        return view('user.project.index', compact('users', 'groups', 'projects', 'plan', 'percent', 'project_limit', 'total_project'));
    }

    public function edit($id)
    {
        $project = Project::with(['projectuser', 'projectgroup'])->findOrFail($id);
        $percentage = $this->storageUsed($project->user_id);
        $plan = Userplan::where('user_id', Auth::id())->first();
        $users = Team::where('creator_id', Auth::id())->with('user')->get();
        $groups = Group::where('user_id', Auth::id())->where('status', 1)->get();
        abort_if($project->user_id != Auth::id(), 401);
        return view('user.project.edit', compact('users', 'groups', 'project', 'percentage', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'project_name' => 'required|max:100',
            'project_description' => 'max:100',
            'project_time' => 'required',
            'project_type' => 'required',
        ]);
        if ($request->project_type == 2) {
            $validated = $request->validate([
                'user' => 'required',
            ]);
        }
        if ($request->project_type == 3) {
            $validated = $request->validate([
                'group' => 'required',
            ]);

            $group = Group::where('user_id', Auth::id())->with('groupitem')->whereIn('id', $request->group)->get();
            $group_users = [];
            foreach ($group as $value) {
                foreach ($value->groupitem as $user) {
                    array_push($group_users, $user->user_id);
                }

                array_push($group_users, Auth::id());
            }
        }

        $date_arr = explode(' - ', $request->project_time);
        $start = $date_arr[0];
        $end = $date_arr[1];

        DB::beginTransaction();
        try {
            $project = Project::findOrFail($id);
            $project->name = $request->project_name;
            $project->description = $request->project_description ?? null;
            $project->starting_date = $start;
            $project->ending_date = $end;
            $project->screenshot = $this->storageUsed(Auth::id()) < 95 ? ($request->screenshot == 'on' ? 1 : 0) : 0;
            $project->project_type = $request->project_type;
            $project->gps = $request->gps == 'on' ? 1 : 0;
            $project->mail_activity = $request->mail_activity == 'on' ? 1 : 0;
            $project->user_id = Auth::id();
            $project->save();

            $existing_user = Projectuser::where('project_id', $project->id)->pluck('user_id')->toArray();
            $seen_user = Projectuser::where('project_id', $project->id)->where('seen', 1)->pluck('user_id')->toArray();
            $existing_group = Projectgroup::where('project_id', $project->id)->pluck('group_id')->toArray();
            $project->user()->detach($existing_user);
            $project->group()->detach($existing_group);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }


        if ($request->project_type == 0) {

            DB::beginTransaction();
            try {
                $project_user = new Projectuser;
                $project_user->project_id = $project->id;
                $project_user->user_id = Auth::id();
                $project_user->seen = 1;
                $project_user->is_admin = 1;
                $project_user->save();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        } elseif ($request->project_type == 2) {
            $users = [];
            $arr = [];
            foreach ($request->user ?? [] as $user) {
                $arr['project_id'] = $project->id;
                $arr['user_id'] = $user;
                $arr['seen'] = in_array($user, $seen_user) ? 1 : 0;
                $arr['is_admin'] = $user == Auth::id() ? 1 : 0;
                $arr['created_at'] = date('Y-m-d H:i:s');
                $arr['updated_at'] = date('Y-m-d H:i:s');
                array_push($users, $arr);
            }


            DB::beginTransaction();
            try {
                $auth['project_id'] = $project->id;
                $auth['user_id'] = Auth::id();
                $auth['seen'] = 1;
                $auth['is_admin'] = 1;
                $auth['created_at'] = date('Y-m-d H:i:s');
                $auth['updated_at'] = date('Y-m-d H:i:s');
                array_push($users, $auth);
                $project->user()->attach($users);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        } elseif ($request->project_type == 3) {
            $groups = [];
            $arr = [];
            foreach ($request->group ?? [] as $group) {
                $arr['project_id'] = $project->id;
                $arr['group_id'] = $group;
                array_push($groups, $arr);
            }


            DB::beginTransaction();
            try {
                $project->group()->attach($groups);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }

            $users = [];
            $arr = [];
            foreach (array_unique($group_users) ?? [] as $user) {
                $arr['project_id'] = $project->id;
                $arr['user_id'] = $user;
                $arr['seen'] = in_array($user, $seen_user) ? 1 : 0;
                $arr['is_admin'] = $user == Auth::id() ? 1 : 0;
                $auth['created_at'] = date('Y-m-d H:i:s');
                $auth['updated_at'] = date('Y-m-d H:i:s');
                array_push($users, $arr);
            }


            DB::beginTransaction();
            try {
                $project->user()->attach($users);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }

        if ($request->mail_activity == 'on') {

            $newuserIds = [];
            $sender = User::where('id', Auth::id())->pluck('email')->first();

            if (count($users) > 0) {
                foreach ($users as $new) {
                    if ($new['seen'] == 0 && !in_array($new['user_id'], $existing_user)) {
                        array_push($newuserIds, $new['user_id']);
                    }
                }

                $newUsers = User::whereIn('id', $newuserIds)->pluck('email');

                foreach ($newUsers as $email) {
                    $data = [
                        'type' => 'assignproject',
                        'email' => $email,
                        'link' => route('user.project.show.userinvite', [$project->id, 1]),
                        'title' => 'You have been assigned on'.$project->name . " Project",
                        'message' => $sender  . " has invited you to " . $project->name . " Project"
                    ];

                    if (env('QUEUE_MAIL') == 'on') {
                        dispatch(new SendEmailJob($data));
                    } else {
                        Mail::to($email)->send(new AssignNewProjectMail($data));
                    }
                }
            }
        }

        return response()->json('Project Updated Successfully....!!');
    }

    public function store(Request $request)
    {
        $project_limit = getplandata('project_limit');
        $total_project = Project::where('user_id', Auth::id())->count();
        if ($total_project >= $project_limit) {
            $msg['errors']['error'] = "Maximum user project limit exceeded";
            return response()->json($msg, 401);
        }
        $validated = $request->validate([
            'project_name' => 'required|max:100',
            'project_description' => 'max:100',
            'project_time' => 'required',
            'project_type' => 'required',
        ]);

        if ($request->project_type == 2) {
            $validated = $request->validate([
                'user' => 'required',
            ]);
        }
        if ($request->project_type == 3) {
            $validated = $request->validate([
                'group' => 'required',
            ]);

            $group = Group::where('user_id', Auth::id())->with('groupitem')->get();
            $group_users = [];
            foreach ($group as $value) {
                foreach ($value->groupitem as $user) {
                    array_push($group_users, $user->user_id);
                }
                array_push($group_users, Auth::id());
            }
        }

        $date_arr = explode(' - ', $request->project_time);
        $start = $date_arr[0];
        $end = $date_arr[1];


        DB::beginTransaction();
        try {
            $project = new Project;
            $project->name = $request->project_name;
            $project->description = $request->project_description ?? null;
            $project->starting_date = $start;
            $project->ending_date = $end;
            $project->screenshot = $this->storageUsed(Auth::id()) < 95 ? ($request->screenshot == 'on' ? 1 : 0) : 0;
            $project->project_type = $request->project_type;
            $project->gps = $request->gps == 'on' ? 1 : 0;
            $project->mail_activity = $request->mail_activity == 'on' ? 1 : 0;
            $project->status = 2;

            $project->user_id = Auth::id();
            $project->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        $users = [];
        if ($request->project_type == 0) {

            DB::beginTransaction();
            try {
                $project_user = new Projectuser;
                $project_user->project_id = $project->id;
                $project_user->user_id = Auth::id();
                $project_user->seen = 1;
                $project_user->is_admin = 1;
                $project_user->save();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        } elseif ($request->project_type == 2) {
            foreach ($request->user ?? [] as $user) {
                $arr['project_id'] = $project->id;
                $arr['user_id'] = $user;
                $arr['seen'] = 0;
                $arr['is_admin'] = 0;
                $arr['created_at'] = date('Y-m-d H:i:s');
                $arr['updated_at'] = date('Y-m-d H:i:s');
                array_push($users, $arr);
            }

            $auth['project_id'] = $project->id;
            $auth['user_id'] = Auth::id();
            $auth['seen'] = 1;
            $auth['is_admin'] = 1;
            $auth['created_at'] = date('Y-m-d H:i:s');
            $auth['updated_at'] = date('Y-m-d H:i:s');
            array_push($users, $auth);

            if (count($users) > 0) {

                DB::beginTransaction();
                try {
                    Projectuser::insert($users);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                }
            }
        } elseif ($request->project_type == 3) {
            $groups = [];
            $arr = [];
            foreach ($request->group ?? [] as $group) {
                $arr['project_id'] = $project->id;
                $arr['group_id'] = $group;
                array_push($groups, $arr);
            }

            if (count($groups) > 0) {

                DB::beginTransaction();
                try {
                    Projectgroup::insert($groups);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                }
            }

            $arr = [];
            foreach (array_unique($group_users) ?? [] as $user) {
                $arr['project_id'] = $project->id;
                $arr['user_id'] = $user;
                $arr['seen'] = 0;
                $arr['is_admin'] = 0;
                $arr['created_at'] = date('Y-m-d H:i:s');
                $arr['updated_at'] = date('Y-m-d H:i:s');
                array_push($users, $arr);
            }


            if (count($users) > 0) {

                DB::beginTransaction();
                try {
                    Projectuser::insert($users);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                }
            }
        }


        // Notify Users after inviting is mail activity is on
        if ($request->mail_activity == "on") {

            $ids = [];
            $sender = User::where('id', Auth::id())->pluck('email')->first();

            if (count($users) > 0) {
                foreach ($users as $user) {
                    array_push($ids, $user['user_id']);
                }
            }

            $added_users = User::whereIn('id', $ids)->pluck('email');

            foreach ($added_users as $email) {
                $data = [
                    'type' => 'assignproject',
                    'email' => $email,
                    'link' => route('user.project.show.userinvite', [$project->id, 1]),
                    'title' => 'You have been assigned on'.$project->name . " Project",
                    'message' => $sender  . " has invited you to " . $project->name . " Project"
                ];


                if (env('QUEUE_MAIL') == 'on') {
                    dispatch(new SendEmailJob($data));
                } else {
                    Mail::to($email)->send(new AssignNewProjectMail($data));
                }
            }
        }

        return response()->json('Project Created Successfully....!!');
    }

    public function show($id)
    {
        $projectuser = Projectuser::where([['project_id', $id], ['user_id', Auth::id()], ['seen', 0]])->first();
        $taskuser = Taskuser::where([['project_id', $id], ['user_id', Auth::id()], ['seen', 0]])->get();
        $taskids = [];
        if ($projectuser) {
            $projectuser->seen = 1;
            $projectuser->save();
        }
        if (count($taskuser) > 0) {
            foreach ($taskuser as $key => $task) {
                array_push($taskids, $task->task_id);
            }

            DB::beginTransaction();
            try {
                Taskuser::whereIn('task_id', $taskids)->update(['seen' => 1]);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }

        $users = Team::where('creator_id', Auth::id())->with('user')->get();
        $project = Project::with(['column', 'projectuser'])->findOrFail($id);
        $isAdmin = $project->projectuser->where('user_id', Auth::id())->first()->is_admin ?? 0;
        $taskusers = Taskuser::where('project_id', $id)->get();
        return view('user.project.show', compact('project', 'taskusers', 'users', 'isAdmin'));
    }

    public function showUserFromMail($id, $task_id, $seen)
    {
        $users = Team::where('creator_id', Auth::id())->with('user')->get();
        $project = Project::with(['column', 'projectuser'])->findOrFail($id);
        $taskusers = Taskuser::where('project_id', $id)->get();

        $task = Taskuser::where([
            ['task_id', $task_id],
            ['user_id', Auth::id()],
            ['project_id', $id],
        ])->first();

        if ($task && $task->seen == 0 && $seen == 1) {
            $task->seen = 1;
            $task->save();
        }

        return view('user.project.show', compact('project', 'taskusers', 'users'));
    }

    public function projectSeenView($id, $seen)
    {
        $users = Team::where('creator_id', Auth::id())->with('user')->get();
        $project = Project::with(['column', 'projectuser'])->findOrFail($id);
        $taskusers = Taskuser::where('project_id', $id)->get();

        $projectst = Projectuser::where([
            ['user_id', Auth::id()],
            ['project_id', $id],
        ])->first();

        if ($projectst->seen == 0 && $seen == 1) {
            $projectst->seen = 1;
            $projectst->save();
        }
        return view('user.project.show', compact('project', 'taskusers', 'users'));
    }

    public function assignAdmin(Request $request)
    {
        $id = $request->project_user_id;
        $project_id = $request->project_id;
        $project = Project::findOrFail($project_id);
        $creator_id = $project->user_id;
        if (empty($id)) {

            DB::beginTransaction();
            try {
                Projectuser::where('project_id', $project_id)->where('user_id', '!=', $creator_id)->update(['is_admin' => 0]);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
            return response()->json('Project updated Successfully....!!');
        }

        DB::beginTransaction();
        try {
            Projectuser::whereNotIn('id', $request->project_user_id)->where([['project_id', $project_id], ['user_id', '!=', $creator_id]])->update(['is_admin' => 0]);
            Projectuser::whereIn('id', $request->project_user_id)->where([['project_id', $project_id], ['user_id', '!=', $creator_id]])->update(['is_admin' => 1]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return response()->json('Project updated Successfully....!!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->back()->with('success', 'Successfully Deleted!');
    }

    public function addColumn(Request $request)
    {

        $column = Column::where('name', $request->name)->where('project_id', $request->project_id)->count();
        if ($column > 0) {
            $msg['errors']['error'] = 'Column already exists';
            return response()->json($msg, 401);
        }

        $position = 1;
        $column = new Column;
        $column->project_id = $request->project_id;
        $column->name = $request->name;
        $column->short = $position;
        $column->status = 2;
        $column->save();
        return response()->json('Successfully added!');
    }

    public function updateColumn($id, Request $request)
    {
        $column = Column::where([
            ['name', $request->name],
            ['project_id', $request->project_id],
            ['id', '!=', $id],
        ])->count();
        if ($column > 0) {
            $msg['errors']['error'] = 'Column already exists';
            return response()->json($msg, 401);
        }
        $column = Column::findOrFail($id);
        $column->name = $request->name;
        $column->save();
        return response()->json('Successfully Updated!');
    }

    public function updateColumnStatus(Request $request)
    {
        if ($request->status == "") {
            return;
        }
        $column = Column::findOrFail($request->id);
        $column->status = $request->status;
        $column->save();
        return 1;
    }

    public function sortColumn(Request $request)
    {
        foreach ($request->id ?? [] as $key => $item) {
            $column = Column::findOrFail((int) $item);
            $column->short = $key++;
            $column->save();
        };
        return true;
    }

    public function deleteColumn($id)
    {
        $deleteColumn = Column::findOrFail($id);
        $deleteColumn->delete();
        return redirect()->back()->with('message', 'Successfully Delete!');
    }

    public function addTask(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);
        $task = new Task;
        $task->column_id = $request->column_id;
        $task->name = $request->name;
        $task->status = 2;
        $task->short = 0;
        $task->task_id = $request->parent_id ?? null;
        $task->priority = 1;
        $task->save();

        $update = Task::findOrFail($task->id);
        $update->short = $task->id ?? 0;
        $update->save();

        return response()->json('Successfully added!');
    }

    public function sortTask(Request $request)
    {

        foreach ($request->task_id ?? [] as $key => $item) {
            if ($item != '') {
                $task = Task::findOrFail((int) $item);
                $task->column_id = (int) $request->column_id ?? 0;
                $task->short = $key++;
                $task->save();
            }
        };
        return true;
    }

    public function updateTask(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);
        $task = Task::findOrFail($request->id);
        $task->name = $request->name;
        $task->save();
        return response()->json('Successfully Updated Task!');
    }

    public function updateTaskStatus(Request $request)
    {
        if ($request->status == "") {
            return;
        }
        $id = $request->id;
        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->save();
        return 1;
    }

    public function updateTaskSt(Request $request)
    {
        $id = $request->id;
        $task = Task::findOrFail($id);
        $project = Project::findOrFail($request->project_id);
        $sender = User::where('id', Auth::id())->first();
        $reciever = User::where('id', $project->user_id)->pluck('email')->first();

        $task->status = $request->status;
        $task->save();

        if ($project->mail_activity == 1 && $reciever != $sender->email) {
            $data = [
                'type' => 'taskcomplete',
                'email' => $reciever,
                'message' => $sender->name . " has submitted " . $task->name . " as completed!"
            ];

            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new SendEmailJob($data));
            } else {
                Mail::to($reciever)->send(new TaskCompleteMail($data));
            }
        }


        return redirect()->back();
    }

    public function updateTaskDueDate(Request $request)
    {
        $due_date = $request->due_date;
        $task_id = $request->task_id;
        $task = Task::findOrFail($task_id);
        $task->due_date = $due_date;
        $task->save();
        return 1;
    }

    public function updateTaskPriority(Request $request)
    {
        $priority = $request->priority;
        $task_id = $request->task_id;
        $task = Task::findOrFail($task_id);
        $task->priority = $priority;
        $task->save();
        return 1;
    }

    public function allTodos(Request $request)
    {
        $project_id = (int) $request->project_id;
        $data = [
            'data' => Task::with('user')->where('task_id', $request->id)->latest()->get(),
            'users' => Projectuser::with('user')->where('project_id', $project_id)->get(),
        ];
        return json_encode($data);
    }

    public function updateTodoTask(Request $request)
    {
        $id = $request->id;
        $todo = Task::findOrFail($id);
        $todo->name = $request->name;
        $todo->save();
        return 1;
    }

    public function assignUserTask(Request $request)
    {
        $user_id = $request->user_id;
        $task_id = $request->task_id;
        $project_id = $request->project_id;
        Taskuser::where('project_id', $project_id)->where('task_id', $task_id)->delete();
        $newUserAssign = new Taskuser;
        $newUserAssign->user_id = $user_id;
        $newUserAssign->task_id = $task_id;
        $newUserAssign->project_id = $project_id;
        $newUserAssign->save();


        $recieverMail = User::where('id', $user_id)->pluck('email')->first();
        $task = Task::findOrFail($task_id);
        $project = Project::where('id', $project_id)->first();
        $senderMail = User::findOrFail($project->user_id);

        if ($project->mail_activity == 1) {
            $data = [
                'seen' => 1,
                'project_id' => $project_id,
                'task_id' => $task_id,
                'type' => 'assigntask',
                'email' => $recieverMail,
                'title' => 'You have been assigned on'.$task->name . " task",
                'message' => $senderMail->name . " has invited you to " . $task->name . " task"
            ];

            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new SendEmailJob($data));
            } else {
                Mail::to($recieverMail)->send(new AssignNewTaskMail($data));
            }
        }
        return 1;
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->back()->with('success', 'Task Deleted Successfully!');
    }

    public function addCommentOnTask(Request $request)
    {
        $request->validate([
            'comment' => 'required|max:100',
            'attachment' => 'mimes:jpeg,png,jpg,txt,pdf,doc,txt,docx,xlxs|max:2048'
        ]);

        $project = Project::findOrFail($request->project_id);
        $attachment_path = $attachment_name = "";



        // If attachment found
        if ($request->hasFile('attachment')) {
            // check storage 
            if ($this->storageUsed($project->user_id) > 95) {
                $msg['errors']['error'] = "Storage Limit exceeded!";
                return response()->json($msg, 401);
            };

            $attachment      = $request->file('attachment');
            $attachment_name = hexdec(uniqid()) . '.' . $attachment->getClientOriginalExtension();
            $attachment_path = 'uploads/' . $project->user_id . '/' . $request->project_id . '/';
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
            'created_at' => $comment->created_at->diffForHumans()
        ];

        return json_encode($data);
    }

    public function storageCheck()
    {
        $projects = Project::where('screenshot', 1)->get();
        foreach ($projects as $project) {
            $dir = 'uploads/' . $project->user_id . '/' . $project->id . '/';
            $storage = $this->folderSize($dir);
            $storage_limit = getplandata('storage_size', $project->user_id);
            // Userplan::where('user_id',$project->user_id)->pluck('storage_size')->first();
            $storage = (float) $storage;
            $percentage = $storage_limit > 0 ? (($storage / $storage_limit) * 100) : 0;
            if ($percentage > 95) {
                $project->screenshot = 0;
                $project->save();
            }
        }
    }

    function storageUsed($user_id = 0)
    {
        $dir = 'uploads/' . $user_id  . '/';
        $storage = $this->folderSize($dir);
        $storage_limit = getplandata('storage_size', $user_id);
        $storage = (float) $storage;
        return $storage_limit > 0 ? (($storage / $storage_limit) * 100) : 0;
    }


    function folderSize($dir)
    {
        $file_size = 0;
        if (!file_exists($dir)) {
            return $file_size;
        }

        foreach (\File::allFiles($dir) as $file) {
            $file_size += $file->getSize();
        }
        return $file_size = str_replace(',', '', number_format($file_size / 1048576, 2));
    }


    public function projectStatus(Request $request)
    {
        $project = Project::findOrFail($request->project_id);
        $project->status = $request->status;
        $project->save();
        return response()->json('Successfully updated!');
    }
}
