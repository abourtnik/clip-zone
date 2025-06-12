<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;

class RateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return RedirectResponse
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60): RedirectResponse
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
