<?php

namespace ChaseH\Http\Middleware;

use Closure;

class PlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $plan
     * @param  string $subscription
     * @return mixed
     */
    public function handle($request, Closure $next, $plan, $subscription = "primary")
    {
        $plans = explode("|", $plan);

        if(!$request->user()->subscribedToPlan($plans, $subscription)) {
            if($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized. No Subscription.', 401);
            } else {
                if($request->user()->subscription($subscription)->cancelled()) {
                    $message = "Sorry, your subscription has expired. You'll need to resume it to see that page.";
                } elseif($request->user()->subscription($subscription)) {
                    $message = "Sorry, your subscription does not include that page. But you can fix that!";
                } else {
                    $message = "You'll need to choose a subscription plan before you do that.";
                }

                return redirect()->route('subs.plans')->withInfo($message);
            }
        }

        return $next($request);
    }
}
