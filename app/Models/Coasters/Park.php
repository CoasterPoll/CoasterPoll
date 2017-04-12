<?php

namespace ChaseH\Models\Coasters;

use ChaseH\Models\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Park extends Model
{
    use SoftDeletes, SearchableTrait;

    protected $table = "parks";

    protected $fillable = [
        'name',
        'short',
        'city',
        'country',
        'website',
        'rcdb_id',
        'img_url',
        'copyright',
    ];

    protected function getSearchable() {
        return [
            'name',
            'city'
        ];
    }

    // ## Relationships
    public function coasters() {
        return $this->hasMany('ChaseH\Models\Coasters\Coaster');
    }
}
