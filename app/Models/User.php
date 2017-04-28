<?php

namespace ChaseH\Models;

use ChaseH\Events\UserCreated;
use ChaseH\Models\Analytics\Demographic;
use ChaseH\Permissions\HasPermissionsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasPermissionsTrait, SearchableTrait, SoftDeletes;

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

    protected $events = [
        'created' => UserCreated::class,
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

    public function hasSocialLinked($service) {
        return (bool) $this->services->where('service', $service)->count();
    }

    public function services() {
        return $this->hasMany(UserSocial::class);
    }

    public function ridden() {
        return $this->belongsToMany('ChaseH\Models\Coasters\Coaster')->withTimestamps();
    }

    public function ranked() {
        return $this->hasMany('ChaseH\Models\Coasters\Rank');
    }

    public function demographics() {
        return $this->belongsTo(Demographic::class, 'demographic_id', 'id');
    }

    public function contacts() {
        return $this->hasMany('ChaseH\Models\Contact');
    }

    public function lockAccount() {
        $this->roles()->detach();

        $this->delete();
    }

    public function unlockAccount() {
        $this->restore();

        $this->roles()->attach(Role::where('name', 'User')->first());
    }
}