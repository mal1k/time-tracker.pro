<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User','creator_id', 'id');
    }
   
    
}
