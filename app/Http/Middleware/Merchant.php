<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Merchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'merchant')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/merchant/login');
        }
    
        return $next($request);
    }
}
