<?php

namespace ChaseH\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    protected $table = "pages";

    protected $fillable = [
        'title',
        'subtitle',
        'url',
        'body',
    ];

    use SoftDeletes;

    public function setUrlAttribute($str) {
        $this->attributes['url'] = strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($str, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }
}
