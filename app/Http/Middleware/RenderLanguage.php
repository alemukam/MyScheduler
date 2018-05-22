<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class RenderLanguage
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
        $lang = $request -> cookie('lang');
        if (!empty($lang)) App::setLocale($lang);

        return $next($request);
    }
}
