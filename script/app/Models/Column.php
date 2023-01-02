<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function task()
    {
        return $this->hasMany('App\Models\Task','column_id','id')->orderBy('short','ASC')->with('user')->withCount('completed_subtask');
    }

    public function comment_with_attachment(){
        return $this->hasMany('App\Models\Task','column_id','id')->with('comment')->whereHas('comment')->with('user');
    }

    
    public function pending_task(){
        return $this->hasMany('App\Models\Task','column_id','id')->with('user')->where('status','!=',1);
    }

    public function pending_task_with_user(){
        return $this->hasMany('App\Models\Task','column_id','id')->with('assigneduser')->whereHas('assigneduser')->where('status','!=',1);
    }

    public function completed_task(){
        return $this->hasMany('App\Models\Task','column_id','id')->where('status',1);
    }

    public function task_name(){
        return $this->hasMany('App\Models\Task','column_id','id')->select('id','column_id','name')->with('assignedTaskUser')->whereHas('assigneduser')->with('timetask');
    }

}
