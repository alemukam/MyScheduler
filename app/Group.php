<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    // Group - User: Relationship one-to-many (user creates a group)
    public function user()
    {
        return $this -> belongsTo('App\User');
    }

    // Group - User: Relationship many-to-many (user is part of many groups)
    public function userRelations()
    {
        return $this -> belongsToMany('App\UserGroupRelation');
    }

    // Group - GroupEvent: Relationship one-to-many (group creates an event)
    public function groupEvents()
    {
        return $this -> hasMany('App\GroupEvent');
    }
}
