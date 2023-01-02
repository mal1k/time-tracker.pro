<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projectuser extends Model
{
    use HasFactory;
    // public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }

    public function project(){
        return $this->belongsTo('App\Models\Project','project_id', 'id');
    }

        
    public function running_project()
    {
        return $this->belongsTo('App\Models\Project','project_id','id')->where('status', 2);
    }
    
    public function completed_project()
    {
        return $this->belongsTo('App\Models\Project','project_id','id')->where('status', 1);
    }

    
}
