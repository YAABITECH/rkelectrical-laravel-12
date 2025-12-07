<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$blockedRoles)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect(route('admin.login'));
        } else{
            $user = Auth::guard('admin')->user();
            if (!$user || empty($request->cookie('admin_hash')) || $request->cookie('admin_hash') !== $user->admin_hash) {
                Auth::guard('admin')->logout();
                return redirect(route('admin.login'))->with('error', 'Please log in to continue.');
            }
            // if(!empty($blockedRoles))
            // {
            //     if (in_array($user->role, $blockedRoles)) {
            //         return back()->with('error', 'You don\'t have the necessary permission');
            //     }
            // }
        }
        return $next($request);
    }
}