<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    // Group - User: Relationship one-to-many (user creates a group)
    public function user()
    {
        return $this -> belongsTo('App\User', 'moderator_id');
    }

    // Group - User: Relationship many-to-many (user is part of many groups)
    public function userRelations()
    {
        return $this -> belongsToMany('App\User', 'user_group_relations') -> withPivot('id', 'status');
    }

    // Group - GroupEvent: Relationship one-to-many (group creates an event)
    public function groupEvents()
    {
        return $this -> hasMany('App\GroupEvent');
    }

    // Group - AdminNotificaion: Relationship one-to-one (only one notification for one group)
    public function adminNotification()
    {
        return $this -> hasOne('App\AdminNotification', 'group_id', 'id');
    }
}
