<?php

namespace App\Http\Middleware;

use Closure;

class CheckBlock
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
        if (auth() -> user() -> status != 'a')
        {
            $request -> session() -> flush(); // remove login session
            return redirect() -> action('NavigationController@homepage');
        }

        return $next($request);
    }
}
