<?php

namespace ChaseH\Http\Middleware;

use ChaseH\Models\Content\Link;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class LinksInViewsMiddleware
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
        $footer_links = Cache::remember('footer-links', 360, function() {
            return Link::where('location', 'footer')->orderBy('order', 'ASC')->get();
        });
        $navbar_links = Cache::remember('navbar-links', 360, function() {
            return Link::where('location', 'navbar')->orderBy('order', 'ASC')->get();
        });

        View::share('_footer_links', $footer_links);
        View::share('_navbar_links', $navbar_links);

        return $next($request);
    }
}
