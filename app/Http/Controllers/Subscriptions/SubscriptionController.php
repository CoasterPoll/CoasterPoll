<?php

namespace ChaseH\Http\Controllers\Subscriptions;

use ChaseH\Models\Subscriptions\Plan;
use ChaseH\Models\Subscriptions\Subscription;
use ChaseH\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index(Request $request) {
        if($request->user !== null && Auth::user()->hasRole('Admin', 'Support')) {
            try {
                $user = User::where('id', $request->user)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }
        } else {
            $user = Auth::user();
        }

        return view('plans.subscriptions', [
            'user' => $user,
        ]);
    }

    public function create(Request $request) {
        try {
            $plan = Plan::where('slug', $request->plan)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $user = Auth::user();

        if($user->subscribedToPlan($plan->stripe_plan, 'primary')) {
            return redirect(route('subs.plans'))->withDanger("Uh oh. Looks like you're already subscribed to that plan.");
        }

        if($user->subscribed('primary')) {
            if($request->has('stripe_token')) {
                $user->updateCard($request->stripe_token);
            }

            $user->subscription('primary')->swap($plan->stripe_plan);
        } else {
            if($request->has('stripe_token')) {
                $user->newSubscription('primary', $plan->stripe_plan)->create($request->stripe_token);
            } else {
                $user->newSubscription('primary', $plan->stripe_plan);
            }
        }

        return redirect(route('subs.plans'))->withSuccess("Thank You! Those changes should appear momentarily.");
    }

    public function cancel(Request $request) {
        if($request->has('user') && Auth::user()->hasRole('Admin', 'Support')) {
            try {
                $user = User::where('id', $request->user)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }
        } else {
            $user = Auth::user();
        }

        try {
            $subscription = Subscription::where('id', $request->subscription)->where('user_id', $user->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $user->subscription($subscription->name)->cancel();

        return back()->withSuccess("We're sorry to see you go!");
    }

    public function resume(Request $request) {
        if($request->has('user') && Auth::user()->hasRole('Admin', 'Support')) {
            try {
                $user = User::where('id', $request->user)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }
        } else {
            $user = Auth::user();
        }

        try {
            $subscription = Subscription::where('id', $request->subscription)->where('user_id', $user->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $user->subscription($subscription->name)->resume();

        return back()->withSuccess("Welcome back!");
    }
}
