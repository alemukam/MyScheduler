<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroupRelation extends Model
{
    // set custom primary key
    protected $primaryKey = ['user_id', 'group_id'];
    public $incrementing = false;

    // Group - User: Relationship many-to-many (user is part of many groups)
    public function userRelations()
    {
        return $this -> hasMany('App\UserGroupRelation');
    }

    // Group - User: Relationship many-to-many (user is part of many groups)
    public function groupRelations()
    {
        return $this -> hasMany('App\UserGroupRelation');
    }
}
