<?php

namespace ChaseH\Models\Subscriptions;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription;

class Plan extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'stripe_plan',
        'cost',
        'description',
        'interval',
        'interval_count',
        'statement_descriptor',
        'trial_days',
    ];

    public function cost() {
        return number_format($this->cost/100, 2, '.', ',');
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class, 'stripe_plan', 'stripe_plan');
    }
}
