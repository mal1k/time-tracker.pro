<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taskscreenshot extends Model
{
    use HasFactory;

    public function screenshot(){
        return $this->belongsTo('App\Models\Screenshot','screenshot_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }
}
