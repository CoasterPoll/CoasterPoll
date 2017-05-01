<?php

namespace ChaseH\Models\Coasters;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = "results";

    protected $fillable = [
        'coaster_id',
        'group',
        'percentage',
        'wins',
        'losses',
        'ties',
        'above',
        'below',
        'equal',
        'flags',
    ];

    public function coaster() {
        return $this->belongsTo('ChaseH\Models\Coasters\Coaster');
    }

    public static function clear($group) {
        self::where('group', $group)->delete();
    }
}
