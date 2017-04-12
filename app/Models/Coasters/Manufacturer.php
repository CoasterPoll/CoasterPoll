<?php

namespace ChaseH\Models\Coasters;

use ChaseH\Models\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use SoftDeletes, SearchableTrait;

    protected $table = "manufacturers";

    protected $fillable = [
        'name',
        'abbreviation',
        'website',
        'rcdb_id',
        'copyright',
        'location',
        'img_url',
    ];

    // ## Relationships
    public function coasters() {
        return $this->hasMany('ChaseH\Models\Coasters\Coaster');
    }
}
