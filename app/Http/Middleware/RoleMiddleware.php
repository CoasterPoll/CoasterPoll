<?php

namespace ChaseH\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  String $role The role a user must have
     * @param  String $permission A specific permission a user must have.
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if(!$request->user()->hasRole($role)) {
            abort(404);
        }

        if($permission !== null && !$request->user()->can($permission)) {
            abort(404);
        }
        return $next($request);
    }
}
