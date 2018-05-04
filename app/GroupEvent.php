<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupEvent extends Model
{
    // Group - GroupEvent: Relationship one-to-many (group creates an event)
    public function group()
    {
        return $this -> belongsTo('App\Group', 'group_id', 'id');
    }
}
