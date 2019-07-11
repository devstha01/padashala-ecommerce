<?php

namespace App\Http\Middleware;

use Closure;

class Staff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'staff')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/admin/login');
        }
        return $next($request);
    }
}
