<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;


    // public function users()
    // {
    //     return $this->belongsToMany('App\Models\User','groupusers','user_id');
    // }

    public function groupmembers()
    {
        return $this->belongsToMany('App\Models\User','groupusers');
    }
    
    public function members()
    {
        return $this->hasMany('App\Models\Groupuser');
    }
    public function groupitem()
    {
        return $this->hasMany('App\Models\Groupuser');
    }
}
