<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;

class RateLimit
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60): Response
    {
        $userId = $request->user()?->id ? '|'. $request->user()->id : '';

        $key = $request->route()->getName() .$userId.':rate';

        if (RateLimiter::tooManyAttempts($key, $perMinute = $maxAttempts)) {
            return back()->with("error", "Too many attempts !");
        }

        RateLimiter::increment($key);

        if (RateLimiter::remaining($key, $maxAttempts) === 0) {
            $seconds = RateLimiter::availableIn($key);
            $sessionKey = $request->route()->getName().$userId.':next';
            Session::put($sessionKey, now()->addSeconds($seconds));
        }

        return $next($request);
    }
}
