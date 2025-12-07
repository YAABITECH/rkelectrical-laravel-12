<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Cookie;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
    protected function authenticate($request, array $guards)
    {
        if ($this->auth->guard('web')->check()) {
            $user = $this->auth->guard('web')->user();
            $loginHash = Cookie::get('login_hash');
            if ($user->login_hash !== $loginHash) {
                $this->logout($request);
            }
        }

        parent::authenticate($request, $guards);
    }
    protected function logout($request)
    {
        $this->auth->guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
