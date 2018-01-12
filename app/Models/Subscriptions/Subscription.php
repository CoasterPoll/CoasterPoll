<?php

namespace ChaseH\Models\Subscriptions;

use Illuminate\Support\Facades\Cache;

class Subscription extends \Laravel\Cashier\Subscription {
    public function plan() {
        return $this->belongsTo(Plan::class, "stripe_plan", "stripe_plan");
    }

    public function renews() {
        return Cache::tags('subscriptions')->remember("subs_ends:".$this->id, 60, function() {
            return \Carbon\Carbon::now()->timestamp($this->asStripeSubscription()->current_period_end)->format('D, M jS');
        });
    }
}