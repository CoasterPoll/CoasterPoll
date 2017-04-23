<?php

namespace ChaseH\Models\Analytics;

use ChaseH\Models\Coasters\Park;
use ChaseH\Models\User;
use Illuminate\Database\Eloquent\Model;

class Demographic extends Model {
    protected $table = "demographics";

    protected $fillable = [
        'age_range',
        'gender',
        'city',
        'latitude',
        'longitude',
        'park_id', // Home park
        'park_visits',
        'unique_parks'
    ];

    public function getAgeRange() {
        return self::$age_ranges[$this->age_range];
    }

    public static $age_ranges = [
        1 => "Under 18",
        2 => "18 - 24",
        3 => "25 - 34",
        4 => "35 - 44",
        5 => "45 - 54",
        6 => "55 - 64",
        7 => "Over 65",
        0 => "Prefer not to answer"
    ];

    public static $genders = [
        0 => "Prefer not to answer",
        1 => "Male",
        2 => "Female",
        3 => "Transgender",
    ];

    public function getGenders() {
        return self::$genders[$this->gender];
    }

    public function homePark() {
        return $this->hasOne(Park::class);
    }

    public function user() {
        return $this->hasOne(User::class);
    }
}