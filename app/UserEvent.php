<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    // UserEvent - User: Relationship one-to-many (user creates an event)
    public function user()
    {
        return $this -> belongsToMany('App\User');
    }
}
