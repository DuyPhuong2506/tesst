<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request params data
     * @param \Closure                 $next    params data
     *
     * @return mixed return next
     */
    public function handle($request, Closure $next)
    {
        $language = $request->get('language', config('locale'));
        \App::setLocale($language);

        return $next($request);
    }
}
