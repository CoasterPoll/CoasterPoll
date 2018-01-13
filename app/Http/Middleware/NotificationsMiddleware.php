<?php

namespace ChaseH\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class NotificationsMiddleware
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
        if(Auth::check()) {
            $notifications = Auth::user()->unreadNotifications;
        } else {
            $notifications = null;
        }

        View::share('_notifications', $notifications);
        return $next($request);
    }
}
