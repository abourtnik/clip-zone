<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Language
{
    public function handle(Request $request, Closure $next)
    {
        $languages = array_keys(config('languages'));

        $locale = Auth::user()?->language
            ?? $request->cookie('app_locale')
            ?? $request->getPreferredLanguage($languages);

        if (!in_array($locale, $languages, true)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
