<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroupRelation extends Model
{
    // Each UserGroup relation has only one user
    public function user()
    {
        return $this -> hasOne('App\User', 'id', 'user_id');
    }

    // Each UserGroup relation has only one group
    public function group()
    {
        return $this -> hasOne('App\Group', 'id', 'group_id');
    }
}
