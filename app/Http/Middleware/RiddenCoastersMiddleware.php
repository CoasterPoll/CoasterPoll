<?php

namespace ChaseH\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class RiddenCoastersMiddleware
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
            $this->addRiddenCoasters();
        }
        return $next($request);
    }

    private function addRiddenCoasters() {
        $ridden = Cache::tags('coasters')->remember('ridden:'.Auth::id(), 2, function() {
            $collection = Auth::user()->ridden->pluck('id');

            $ids = array();
            foreach($collection as $item) {
                $ids[$item] = $item;
            }
            return $ids;
        });

        View::share('ridden_coasters', $ridden);
    }
}
