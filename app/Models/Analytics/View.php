<?php

namespace ChaseH\Models\Analytics;

use ChaseH\Models\User;
use ChaseH\Stretch\Stretchy;
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

    protected $searchableUsing = "stretch";

    public function toSearchableArray() {
        $this->load('user');

        $basic = [
            'page' => $this->page,
            'loadtime' => $this->time,
            'query' => $this->query,
            'hash' => $this->hash,
            'referrer' => $this->referrer,
            'session' => $this->session,
            'time' => $this->created_at->subSecond(3)->timestamp,
        ];

        if($this->user) {
            $user = [
                'user' => true,
                'handle' => $this->user->handle,
                'account_age' => $this->user->created_at->diffInDays(),
            ];
        } else {
            $user = [
                'user' => false,
                'handle' => "",
                'account_age' => 0
            ];
        }

        return array_merge($basic, $user);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}