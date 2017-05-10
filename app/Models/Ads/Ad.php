<?php

namespace ChaseH\Models\Ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use SoftDeletes;

    protected $table = "ads";

    protected $fillable = [
        'name',
        'img_url',
        'img_alt',
        'img_href',
        'sponsor',
        'sponsor_href',
        'campaign_id'
    ];

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }
}
