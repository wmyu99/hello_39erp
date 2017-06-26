<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Role;

class Permission extends Model
{
    return $this->belongsToMany('App\Role');
    
}
