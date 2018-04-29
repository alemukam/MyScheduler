<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $user_role = 'user_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    // Group - User: Relationship one-to-many (user creates a group)
    public function groups()
    {
        return $this -> hasMany('App\Group', 'moderator_id', 'id');
    }

    // Group - User: Relationship many-to-many (user is part of many groups)
    public function groupRelations()
    {
        return $this -> belongsToMany('App\Group', 'user_group_relations') -> withPivot('id','status');
    }

    // UserEvent - User: Relationship one-to-many (user creates an event)
    public function userEvents()
    {
        return $this -> hasMany('App\UserEvent');
    }
}
