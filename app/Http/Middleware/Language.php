<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    public function handle($request, Closure $next)
    {
        if (session()->has('app_locale') && array_key_exists(session()->get('app_locale'), config('languages'))) {
            App::setLocale(session()->get('app_locale'));
            return $next($request);
        }

        App::setLocale(config('app.fallback_locale'));
        return $next($request);
    }
}
