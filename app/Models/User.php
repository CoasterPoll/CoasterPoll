<?php

namespace ChaseH\Models;

use ChaseH\Permissions\HasPermissionsTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasPermissionsTrait, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function getSearchable() {
        return [
            'name',
            'email'
        ];
    }

    public function promoteToAdmin($role = "Admin") {
        $this->roles()->attach(Role::where('name', $role)->first());
    }

    public function ridden() {
        return $this->belongsToMany('ChaseH\Models\Coasters\Coaster')->withTimestamps();
    }
}
