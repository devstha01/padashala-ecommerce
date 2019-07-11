<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public
    function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $role = Auth::guard('admin')->user()->hasRole('Admin', 'admin') ?? false;
            if (!$role) return redirect()->back()->with('info', __('dashboard.Unauthorized action'));
        }
        return $next($request);
    }
}
