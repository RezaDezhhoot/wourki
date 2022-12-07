<?php

namespace App\Http\Middleware;

use Closure;

class redirectIfVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \auth()->guard('web')->user();
        if ($user->mobile_confirmed == 1) {
            return redirect()->route('user.profile');
        } else {
            return $next($request);

        }
    }
}
