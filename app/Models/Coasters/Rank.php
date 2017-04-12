<?php

namespace ChaseH\Models\Coasters;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    protected $table = "rankings";

    protected $fillable = [
        'coaster_id',
        'user_id',
        'rank'
    ];

    // ## Relationships
    public function user() {
        return $this->belongsTo('ChaseH\Models\User');
    }

    public function coaster() {
        return $this->belongsTo('ChaseH\Models\Coasters\Coaster');
    }
}
