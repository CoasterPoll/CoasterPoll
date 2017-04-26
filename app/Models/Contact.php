<?php

namespace ChaseH\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model {
    use SoftDeletes;

    protected $table = "contacts";

    protected $fillable = [
        'name',
        'email',
        'user_id',
        'message',
        'extra',
    ];

    protected $casts = [
        'extra' => 'array'
    ];

    public function contactable() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo('ChaseH\Models\User');
    }
}
