<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    public $timestamps = null;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }

    public function project(){
        return $this->belongsTo('App\Models\Project','project_id', 'id');
    }

    public function timetask(){
        return $this->hasMany('App\Models\Timetask','time_id', 'id');
    }

}
