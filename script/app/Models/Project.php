<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function column()
    {
        return $this->hasMany('App\Models\Column','project_id','id')->orderBy('short','ASC')->with('task')->withCount('pending_task');
    }

    public function column_with_task()
    {
        return $this->hasMany('App\Models\Column','project_id','id')->orderBy('short','ASC')->whereHas('task_name')->with('task_name')->select('id','project_id');
    }

    public function completed_column(){
        return $this->hasMany('App\Models\Column','project_id','id')->where('status',1);
    }

    public function projectuser(){
        return $this->hasMany('App\Models\Projectuser','project_id', 'id');
    }

    public function projectgroup(){
        return $this->hasMany('App\Models\Projectgroup','project_id', 'id');
    }

    public function user(){
        return $this->belongsToMany('App\Models\User','projectusers')->withTimestamps();
    }

    public function group(){
        return $this->belongsToMany('App\Models\Group','projectgroups');
    }

    public function time(){
        return $this->belongsToMany('App\Models\Time','project_id', 'id');
    }

}
