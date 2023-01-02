<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projectgroup extends Model
{
    use HasFactory;

    public function group(){
        return $this->belongsTo('App\Models\Group','group_id', 'id')->with('groupitem');
    }
}
