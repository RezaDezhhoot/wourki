<?php

namespace App\Http\Middleware;

use Closure;

class DisplayDebugbar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->guard('admin')->check() && auth()->guard('admin')->user()->is_developer == 1){
            app('debugbar')->enable();
        }else{
            app('debugbar')->disable();
        }
        return $next($request);
    }
}
