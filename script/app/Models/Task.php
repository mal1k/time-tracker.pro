<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Task extends Model
{
    use HasFactory;


    public function task()
    {
        return $this->hasMany('App\Models\Task','task_id','id')->orderBy('id','DESC');
    }

    public function completed_subtask()
    {
        return $this->hasMany('App\Models\Task','task_id','id')->where('status', 1);
    }

    public function user(){
        return $this->hasOne('App\Models\Taskuser','task_id','id')->with('user');
    }

    public function assigneduser(){
        return $this->hasOne('App\Models\Taskuser','task_id','id')->with('user')->where('user_id', Auth::id());
    }

    public function assignedTaskUser(){
        return $this->hasOne('App\Models\Taskuser','task_id','id')->where('user_id', Auth::id())->select('task_id','user_id');
    }

    
    public function comment()
    {
        return $this->hasMany('App\Models\Comment','task_id','id')->orderBy('id','DESC')->with('attachment');
    }

    public function taskscreenshot(){
        return $this->hasMany('App\Models\Task','task_id','id');
    }


    public function username(){
        return $this->hasOne('App\Models\Taskuser','task_id','id')->with('user')->where('user_id', Auth::id());
    }

    public function timetask(){
        return $this->hasMany('App\Models\Timetask','task_id','id')->with('time');
    }
}
