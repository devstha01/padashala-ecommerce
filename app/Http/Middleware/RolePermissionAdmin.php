<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RolePermissionAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {
        if (Auth::guard('admin')->check()) {
            $perm = Auth::guard('admin')->user()->hasPermissionTo($permission, 'admin') ?? false;
            $role = Auth::guard('admin')->user()->hasRole('Admin', 'admin') ?? false;
            if ((!$perm && !$role)) return redirect()->back()->with('info', __('dashboard.Unauthorized action'));
        }
        return $next($request);
    }
}
