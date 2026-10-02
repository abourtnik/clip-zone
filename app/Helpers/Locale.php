<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class Locale
{
    public static function resolve(Request $request): string
    {
        $available = array_keys(config('languages'));

        $locale = $request->user()?->language
            ?? $request->cookie('app_locale')
            ?? $request->getPreferredLanguage($available);

        return in_array($locale, $available, true)
            ? $locale
            : config('app.fallback_locale');
    }
}
