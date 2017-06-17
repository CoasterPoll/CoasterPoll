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
        'img_path',
    ];

    public function getLink() {
        return route('coasters.manufacturer.id', ['id' => $this->id]);
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
    public function coasters() {
        return $this->hasMany('ChaseH\Models\Coasters\Coaster');
    }

    public function links() {
        return $this->morphMany('Chaseh\Models\Sharing\Link', 'linkable');
    }
}
