<?php

namespace ChaseH\Models\Sharing;

use ChaseH\Models\User;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = "link_votes";

    protected $fillable = [
        'link_id',
        'comment_id',
        'voter_id',
        'direction',
    ];

    public function voter() {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function link() {
        return $this->belongsTo(Link::class);
    }

    public function comment() {
        return $this->belongsTo(Comment::class);
    }
}
