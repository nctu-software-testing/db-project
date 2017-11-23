<?php

namespace App\Http\Middleware\Auth;

use App\User;
use Closure;

class CustomerUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (session('user.role') !== User::CUSTOMER_ROLE) {
            return redirect('/');
        }

        return $next($request);
    }
}
