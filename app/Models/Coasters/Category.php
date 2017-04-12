<?php

namespace ChaseH\Models\Coasters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes;

    protected $table = "categories";

    protected $fillable = [
        'name'
    ];

    // ## Relationships
    public function coasters()
    {
        return $this->belongsToMany('ChaseH\Models\Coasters\Coaster');
    }
}