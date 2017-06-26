<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	  public function permissions()
	    {
            return $this->belongsToMany('App\Permissions');
         }
	  public function givePermissionTo($permission)
		{
			return $this->permissions()->save($permission);
		}

}