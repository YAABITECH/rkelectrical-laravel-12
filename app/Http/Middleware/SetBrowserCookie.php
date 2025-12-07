<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SetBrowserCookie
{
    public function handle(Request $request, Closure $next)
    {
        $browserCookie = $request->cookie('browser_cookie');

        if (!$browserCookie) {
            $browserCookie = Str::random(32);
            $cookie = Cookie::make('browser_cookie', $browserCookie, 60*24*365, '/', null, false, false);
            return $next($request)->cookie($cookie);
        }

        return $next($request);
    }
}