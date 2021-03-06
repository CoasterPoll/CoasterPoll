<?php

namespace ChaseH\Models;

use ChaseH\Events\UserCreated;
use ChaseH\Models\Analytics\Demographic;
use ChaseH\Models\Subscriptions\Subscription;
use ChaseH\Permissions\HasPermissionsTrait;
use ChaseH\Stretch\Stretchy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasPermissionsTrait, Stretchy, PreferenceTrait, SoftDeletes, Billable, HasApiTokens, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'handle', 'email', 'password', 'preferences'
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

    protected $searchableUsing = "stretch";

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

    public function shared_links() {
        return $this->hasMany('ChaseH\Models\Sharing\Link', 'posted_by');
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

    public function getProfileLink() {
        if($this->deleted_at != null) {
            return "#";
        }

        return route('profile', ['handle' => $this->handle]);
    }

    public function links() {
        return $this->morphMany('ChaseH\Models\Sharing\Link', 'linkable');
    }
}