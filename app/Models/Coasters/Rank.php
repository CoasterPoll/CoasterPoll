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

    public function getName() {
        return $this->coaster->getName();
    }

    public function getParkName() {
        return $this->coaster->getParkName();
    }

    public function getManufacturerName() {
        return $this->coaster->getManufacturerName();
    }

    public function getRank() {
        return $this->rank;
    }

    public function getId() {
        return $this->coaster_id;
    }

    // ## Relationships
    public function user() {
        return $this->belongsTo('ChaseH\Models\User');
    }

    public function coaster() {
        return $this->belongsTo('ChaseH\Models\Coasters\Coaster');
    }
}
