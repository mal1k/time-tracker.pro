<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userplan extends Model
{
    use HasFactory;
    public $timestamps = false;

    // public function plan(){
    //     return $this->belongsTo('App\Models\Plan','user_id', 'id');
    // }
}
