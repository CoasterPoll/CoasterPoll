<?php

namespace ChaseH\Http\Middleware;

use Closure;

class SocialMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!in_array(strtolower($request->service), array_keys(config('social.services')))) {
            return redirect(route('login'))->withErrors("Sorry. That's not a valid provider.");
        }

        return $next($request);
    }
}
