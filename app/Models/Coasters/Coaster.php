<?php

namespace ChaseH\Models\Coasters;

use ChaseH\Models\LinkableTrait;
use ChaseH\Models\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Coaster extends Model
{
    use SoftDeletes, SearchableTrait, Searchable, LinkableTrait;

    protected $table = "coasters";

    protected $fillable = [
        'name',
        'year',
        'rcdb_id',
        'park_id',
        'manufacturer_id',
        'type_id',
        'copyright',
        'img_url',
        'img_path',
        'slug'
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

    public function getLink() {
        return route('coasters.coaster.id', ['id' => $this->id]);
    }

    public function hasImg() {
        if($this->img_path !== null || $this->img_url !== null) {
            return true;
        }

        return false;
    }

    public function getImg() {
        if($this->img_path !== null) {
            return env('IMG_URL')."/".$this->img_path;
        }

        if($this->img_url !== null) {
            return $this->img_url;
        }

        return null;
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

    public function contact() {
        return $this->morphMany('ChaseH\Models\Contact', 'contactable');
    }

    public function links() {
        return $this->morphMany('ChaseH\Models\Sharing\Link', 'linkable');
    }

    // ## Traits
    protected function getSearchable() {
        return [
            'name',
            'short',
        ];
    }

    public function searchableAs() {
        return "coasters";
    }

    public function searchableAsType() {
        return "coaster";
    }

    public function toSearchableArray()
    {
        $this->load('park');
        $this->load('type');
        $this->load('categories');
        $this->load('manufacturer');

        /**
         * Load the categories relation so that it's available
         *  in the laravel toArray method
         */
        $cats = [];
        $cats['categories'] = array_map(function ($data) {
            return $data['name'];
        }, $this->categories->toArray());

        return array_merge($this->toArray(), $cats, ['riders' => $this->riders->count()]);
    }
}
