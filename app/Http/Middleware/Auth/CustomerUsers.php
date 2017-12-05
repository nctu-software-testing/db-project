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
        $role = session('user.role');
        if ($role !== User::CUSTOMER_ROLE && $role!==User::ADMIN_ROLE) {
            return redirect('/');
        }

        return $next($request);
    }
}
