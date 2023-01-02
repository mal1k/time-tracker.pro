<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\CollabMail;
use App\Mail\InviteUserMail;
use App\Models\Group;
use App\Models\Groupuser;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class TeamController extends Controller
{
    public function index()
    {
        $team = Team::where([['user_id', Auth::id()],['status', 2]])->first();
        if($team){
            $team->status = 1;
            $team->save();
        }
        return view('user.team.index');
    }
    public function member()
    {
        $members = Team::where('creator_id', Auth::id())->get();
        return view('user.team.member', compact('members'));
    }

    public function reminder()
    {
        return view('user.team.reminder');
    }
    public function collaboration()
    {
        $collaborations = Team::with('creator')->where('user_id', Auth::id())->get();
        return view('user.team.collaboration', compact('collaborations'));
    }

    public function collaboration_activate(Request $request)
    {
        $token = $request->token;
        $decrypted = Crypt::decryptString($token);
        $creator_id = $user_id = '';
        if ($decrypted) {
            $arr = explode(",", $decrypted);
            $creator_id = $arr[0];
            $user_id = $arr[1];
        }
        abort_if($user_id != Auth::id(), 403);
        $team = Team::where('user_id', $user_id)->where('creator_id', $creator_id)->first();
        $team->status = 1;
        $team->save();
        return redirect()->route('user.team.collaboration');
    }

    public function collaboration_search(Request $request)
    {
        $q = $request->q;
        $type = $request->type;
        $collaborations = Team::whereHas('creator')->where('user_id', Auth::id());
        if ($type == 'username') {
            $collaborations = $collaborations->whereHas('creator', function($query) use ($q){
                return $query->where('name','LIKE',"%$q%");
            })->get();
        }elseif ($type == 'email') {
            $collaborations = $collaborations->whereHas('creator', function($query) use ($q){
                return $query->where('email','LIKE',"%$q%");
            })->get();
        }
       
        return view('user.team.collaboration', compact('collaborations'));
    }



    public function collaboration_delete($id)
    {
        $collab = Team::findOrFail($id);
        abort_if($collab->user_id != Auth::id(), 403);
        $collab->delete();
        return redirect()->back()->with('message', 'Left Successfully!');
    }

    public function store(Request $request)
    {
        $user_limit = getplandata('user_limit');
        $total_users = Team::where('creator_id', Auth::user()->id)->count();
        if ($total_users >= $user_limit) {
            $msg['errors']['error'] = "Maximum user limit exceeded";
            return response()->json($msg, 401);
        }

        $request->validate([
            'email'            => 'required',
            'hourly_rate'     => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!empty($user)) {
            $team = Team::where('user_id', $user->id)->where('creator_id', Auth::id())->first();

            if ($team) {
                $msg['errors']['error'] = "User already exists";
                return response()->json($msg, 401);
            }



            if ($user->id == Auth::user()->id) {
                $msg['errors']['error'] = "Member and Creator cannot be the same";
                return response()->json($msg, 401);
            }

            $store = new Team();
            $store->user_id = $user->id;
            $store->creator_id = Auth::user()->id;
            $store->h_rate = $request->hourly_rate;
            $store->status = 2;  // 1 = active / 2=pending /0 = inactive  
            $store->save();
            
        }


        if (!$user) {
            $string = Auth::user()->id . "," . $request->hourly_rate;
            $link = Crypt::encryptString($string);

            $data = [
                'type' => 'invite',
                'name' => Auth::user()->name,
                'email' => $request->email,
                'link' => route('invite.register', "token=" . $link),
                
            ];
            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new SendEmailJob($data));
            } else {
                Mail::to($request->email)->send(new InviteUserMail($data));
            }

            $msg['errors']['error'] = "User not found! Invitation Sent!";
            return response()->json($msg, 401);
        } else {
            $data = [
                'name' => Auth::user()->name,
                'type' => 'collab',
                'email' => $request->email,
                'link' => route('user.team.collab.activate', "token=" . Crypt::encryptString(Auth::user()->id . "," . $user->id)),
            ];
            
            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new SendEmailJob($data));
            } else {
                Mail::to($request->email)->send(new CollabMail($data));
            }
            return response()->json("Invitation Send");
        }
    }

    public function edit($id)
    {
        $edit = Team::findOrfail($id);
        return view('user.team.edit-member', compact('edit'));
    }

    public function update(Request $request, $member)
    {
        $request->validate([
            'email'            => 'required',
            'hourly_rate'     => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $msg['errors']['error'] = "User Not Found";
            return response()->json($msg, 401);
        }
        $team = Team::where([
            ['user_id', $user->id],
            ['id', '!=', $member],
            ['creator_id', Auth::user()->id],
        ])->first();
        if ($team) {
            $msg['errors']['error'] = "User already exists";
            return response()->json($msg, 401);
        }
        if ($user->id == Auth::user()->id) {
            $msg['errors']['error'] = "Creator and Member cannot be the same";
            return response()->json($msg, 401);
        }

        $update = Team::where('creator_id', Auth::id())->findOrfail($member);
        $update->user_id = $user->id;
        $update->h_rate = $request->hourly_rate;
        $update->update();
        return response()->json('Update Successful');
    }

    public function delete($member)
    {
        $delete = Team::where('creator_id', Auth::id())->findOrfail($member);
        $delete->delete();
        return redirect()->back()->with('message', 'Successfully Removed!');
    }

    // Group 
    public function group()
    {
        $groups = Group::where('user_id', Auth::id())->with('groupmembers')->latest()->get();
        $members = Team::where('creator_id', Auth::id())->get();
        return view('user.team.group', compact('members', 'groups'));
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $type = $request->type;
        $members = Team::whereHas('user')->where('creator_id', Auth::id());
        if ($type == 'username') {
            $members = $members->whereHas('user', function($query) use ($q){
                return $query->where('name','LIKE',"%$q%");
            })->get();
        }elseif ($type == 'email') {
            $members = $members->whereHas('user', function($query) use ($q){
                return $query->where('email','LIKE',"%$q%");
            })->get();
        }
       
        return view('user.team.member', compact('members'));
    }

    public function groupsearch(Request $request)
    {
        $q = $request->q;
        $type = $request->type;

        $members = Team::where('creator_id', Auth::id());
        $groups = Group::where('user_id', Auth::id())->with('groupmembers');
       
        if ($type == 'group') {
            $groups = $groups->where('name','LIKE',"%$q%")->get();
            $members = $members->get();
        }elseif ($type == 'member') {
            $groups = $groups->get();
            $members = $members->whereHas('user', function($query) use ($q){
                return $query->where('email','LIKE',"%$q%");
            })->get();
        }


        return view('user.team.group', compact('members', 'groups'));
    }

    public function groupStore(Request $request)
    {
        $group_limit = getplandata('group_limit');
        $total_groups = Group::where('user_id', Auth::user()->id)->count();
        if ($total_groups >= $group_limit) {
            $msg['errors']['error'] = "Maximum group limit exceeded";
            return response()->json($msg, 401);
        }


        $request->validate([
            'group'            => 'required',
            'group_members'     => 'required',
        ]);
        $group = new Group();
        $group->user_id = Auth::user()->id;
        $group->name = $request->group;
        $group->status = 1;
        $group->save();
        $members = [];
        foreach ($request->group_members as $key => $value) {
            $members[] = [
                'user_id' => $key,
                'group_id' => $group->id
            ];
        }
        Groupuser::insert($members);

        return response()->json('Successfully Added');
    }

    public function groupEdit($id)
    {
        $group = Group::where('user_id', Auth::id())->findOrFail($id);
        $members = Team::where('creator_id', Auth::id())->get();
        return view('user.team.group_edit', compact('members', 'group'));
    }
    public function groupUpdate($id, Request $request)
    {
        Groupuser::where('group_id', $id)->delete();
        $group = Group::findOrFail($id);
        $group->name = $request->group;
        $group->save();

        $members = [];
        foreach ($request->group_members as $key => $value) {
            $members[] = [
                'user_id' => $key,
                'group_id' => $group->id
            ];
        }
        Groupuser::insert($members);
        return response()->json('Successfully Updated');
    }

    public function groupDelete($id)
    {
        Group::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->back()->with('message', 'Delete Successfully!');
    }
}
