<?php

namespace ChaseH\Models\Coasters;

use ChaseH\Models\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coaster extends Model
{
    use SoftDeletes, SearchableTrait;

    protected $table = "coasters";

    protected $fillable = [
        'name',
        'year',
        'rcdb_id',
        'copyright',
        'img_url',
    ];

    // ## Functions
    public static function rankedBy(int $userid, string $with = null) {
        $return = self::whereHas('rankings', function($query) use ($userid) {
            $query->where('user_id', $userid)->orderBy('overall', 'ASC');
        })->with(['rankings' => function($query) use ($userid) {
            $query->where('user_id', $userid);
        }]);

        if(!is_null($with)) {
            $return->with($with);
        }

        return $return->get();
    }

    // ## Relationships
    public function park() {
        return $this->belongsTo('ChaseH\Models\Coasters\Park');
    }

    public function type() {
        return $this->belongsTo('ChaseH\Models\Coasters\Type');
    }

    public function categories() {
        return $this->belongsToMany('ChaseH\Models\Coasters\Category')->withTimestamps();
    }

    public function manufacturer() {
        return $this->belongsTo('ChaseH\Models\Coasters\Manufacturer');
    }

    public function riders() {
        return $this->belongsToMany('ChaseH\Models\User')->withTimestamps();
    }

    public function rankings() {
        return $this->hasMany('ChaseH\Models\Coasters\Rank');
    }

    // ## Traits
    protected function getSearchable() {
        return [
            'name',
            'short',
        ];
    }
}
