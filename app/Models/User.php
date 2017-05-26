<?php

namespace ChaseH\Models;

use ChaseH\Events\UserCreated;
use ChaseH\Models\Analytics\Demographic;
use ChaseH\Models\Subscriptions\Subscription;
use ChaseH\Permissions\HasPermissionsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, HasPermissionsTrait, SearchableTrait, PreferenceTrait, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'preferences'
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

    protected $casts = [
        'preferences' => 'json',
    ];

    protected static function getSearchable() {
        return [
            'name',
            'email'
        ];
    }

    public function promoteToAdmin($role = "Admin") {
        $this->roles()->attach(Role::where('name', $role)->first());

        Cache::forget('roles:'.$this->id);
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

    public function subscriptions() {
        return $this->hasMany(Subscription::class, $this->getForeignKey())->orderBy('created_at', 'desc');
    }
}