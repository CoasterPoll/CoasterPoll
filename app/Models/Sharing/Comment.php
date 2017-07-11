<?php

namespace ChaseH\Models\Sharing;

use ChaseH\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'body',
        'commentable_id',
        'commentable_type',
    ];

    public function commentable() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent() {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }

    public function getId() {
        return base_convert($this->id, 10, 32);
    }
}
