<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetask extends Model
{
    use HasFactory;

    public $timestamps = null;

    public function task(){
        return $this->belongsTo('App\Models\Task','task_id', 'id');
    }

    public function time(){
        return $this->belongsTo('App\Models\Time','time_id', 'id')->select('id','total_time');
    }
}
