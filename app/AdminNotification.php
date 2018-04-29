<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    // Group - AdminNotificaion: Relationship one-to-one (only one notification for one group)
    public function group()
    {
        return $this -> belongsTo('App\Group', 'id', 'group_id');
    }
}
