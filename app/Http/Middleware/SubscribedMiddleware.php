<?php

namespace ChaseH\Http\Middleware;

use Closure;

class SubscribedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $subscription
     * @return mixed
     */
    public function handle($request, Closure $next, $subscription = "primary")
    {
        if(!$request->user()->subscribed($subscription)) {
            if($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized. No Subscription.', 401);
            } else {
                if($request->user()->subscription($subscription)->cancelled()) {
                    $message = "Sorry, your subscription has expired. You'll need to resume it to see that page.";
                } else {
                    $message = "You'll need to choose a subscription plan before you do that.";
                }

                return redirect()->route('subs.plans')->withInfo($message);
            }
        }

        return $next($request);
    }
}
