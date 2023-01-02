<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taskuser extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    // public $timestamps = false;

    public function task()
    {
        return $this->belongsTo('App\Models\Task','task_id','id');
    }

    public function pending_task()
    {
        return $this->belongsTo('App\Models\Task','task_id','id')->where('status', 2);
    }

    public function completed_task()
    {
        return $this->belongsTo('App\Models\Task','task_id','id')->where('status', 1);
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project','project_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
