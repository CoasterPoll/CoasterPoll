<?php

namespace ChaseH\Models\Sharing;

use ChaseH\Models\DummyUser;
use ChaseH\Models\User;
use ChaseH\Traits\Eloquent\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Link extends Model
{
    protected $table = "shared_links";

    protected $fillable = [
        'title',
        'slug',
        'body',
        'link',
        'score',
        'posted_by',
        'linkable_id',
        'linkable_type',
    ];

    protected $states = [
        0 => "Hidden",
        1 => "Public"
    ];

    use SoftDeletes, NestableTrait;

    public function poster() {
        return $this->belongsTo(User::class, "posted_by")->withTrashed();
    }

    public function linkable() {
        return $this->morphTo();
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }

    public function reports() {
        return $this->hasMany(LinkReport::class);
    }

    public function getPoster() {
        if($this->posted_by !== null) {
            return $this->poster;
        }

        return new DummyUser();
    }

    public static function whereActive() {
        return self::where('state', 1);
    }

    public static function whereInactive() {
        return self::where('state', 0);
    }

    public static function whereDeleted() {
        return self::where('state', 1)->withTrashed();
    }

    public function getId() {
        return base_convert($this->id, 10, 32);
    }

    public function getCid() {
        return "L".$this->id;
    }

    public function getLink() {
        return route('links.link.view', ['link' => $this->getId(), 'slug' => $this->slug]);
    }

    public function out() {
        if($this->link == null) {
            return $this->getLink();
        }

        return $this->link;
    }

    public function body() {
        return \Parsedown::instance()->text($this->body);
    }

    public function authVote() {
        if(!Auth::check()) {
            return null;
        }

        return Vote::where('voter_id', Auth::id())->where('link_id', $this->id)->first();
    }

    public function getVoteClass($btn) {
        $vote = $this->authVote();

        if($vote == null) {
            return "";
        }

        if($vote->direction > 0 && $btn == "up") {
            return "text-success";
        }

        if($vote->direction < 0 && $btn == "down") {
            return "text-success";
        }

        return "";
    }
}
