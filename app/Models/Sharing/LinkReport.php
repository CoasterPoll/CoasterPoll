<?php

namespace ChaseH\Models\Sharing;

use ChaseH\Models\User;
use Illuminate\Database\Eloquent\Model;

class LinkReport extends Model
{
    protected $table = "link_reports";

    protected $fillable = [
        'user_id',
        'link_id',
        'comment_id',
        'reason',
    ];

    public function link() {
        return $this->belongsTo(Link::class);
    }

    public function comment() {
        return $this->belongsTo(Comment::class);
    }

    public function reporter() {
        return $this->belongsTo(User::class);
    }
}
