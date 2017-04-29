<?php

namespace ChaseH\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    protected $table = "links";

    protected $fillable = [
        'text',
        'href',
        'location',
        'order'
    ];

    use SoftDeletes;
}
