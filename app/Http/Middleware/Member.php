<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Member
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if (!Auth::user()) {
            return redirect('/login');
        } else if (Auth::user()->is_member === 0)
            return redirect('/');


        if (Auth::user()->jwt_token_handle !== '')
            return redirect(route('customer-logout'));

        return $next($request);
    }
}
