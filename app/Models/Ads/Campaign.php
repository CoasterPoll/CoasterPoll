<?php

namespace ChaseH\Models\Ads;

use Carbon\Carbon;
use ChaseH\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $table = "campaigns";

    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'cost',
        'paid',
        'budget',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_at',
        'end_at',
    ];

    public function admins() {
        return $this->belongsToMany(User::class);
    }

    public function ads() {
        return $this->hasMany(Ad::class);
    }

    // Functions?
    public function isActive() {
        // If we're pre-start or post-end, nope!
        if(!Carbon::now()->between($this->start_at, $this->end_at)) {
            return false;
        }

        // If we haven't paid what this campaign will cost.
        if($this->paid < $this->cost) {
            return false;
        }

        // We made it through all the things!
        return true;
    }

    public function progressAsPercentage() {
        if($this->end_at->isPast()) {
            return 100;
        }
        if($this->start_at->isFuture()) {
            return 0;
        }

        $current = $this->start_at->diffInSeconds(Carbon::now());
        $overall = $this->start_at->diffInSeconds($this->end_at);
        //dd($current, $overall);
return (($current / $overall) * 100);
        dd($overall / $current);
        dd(((Carbon::now()->timestamp - $this->start_at->timestamp) / ($this->end_at->timestamp - $this->start_at->timestamp)) * 100 );
    }
}
