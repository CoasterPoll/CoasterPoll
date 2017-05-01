<?php

namespace ChaseH\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use Notifiable;

    protected $fillable = [
        'name',
        'default',
    ];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function routeNotificationForSlack()
    {
        return env('SLACK_ADMIN_WEBHOOK');
    }
}
