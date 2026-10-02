<?php

namespace App\Http\Middleware;

use App\Helpers\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Language
{
    public function handle(Request $request, Closure $next)
    {
        App::setLocale(Locale::resolve($request));

        return $next($request);
    }
}
