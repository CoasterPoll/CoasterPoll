<?php

namespace ChaseH\Models\Analytics;

use ChaseH\Models\User;
use Illuminate\Database\Eloquent\Model;

class View extends Model {
    protected $table = "views";

    protected $fillable = [
        'page',
        'time',
        'user_id',
        'query',
        'hash',
        'referrer',
        'session'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }
}