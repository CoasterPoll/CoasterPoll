<?php

namespace ChaseH\Models\Coasters;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = "types";

    protected $fillable = [
        'name'
    ];

    public function coasters() {
        return $this->hasMany('ChaseH\Models\Coasters\Coaster');
    }
}
