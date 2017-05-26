<?php

namespace ChaseH\Http\Controllers\Subscriptions;

use ChaseH\Models\Subscriptions\Plan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index() {
        return view('plans.index', [
            'plans' => Plan::get(),
        ]);
    }

    public function show($plan) {
        try {
            $plan = Plan::where('slug', $plan)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return view('plans.plan', [
            'plan' => $plan,
        ]);
    }

    public function test() {
        dd("You've made it to this page!");
    }
}
