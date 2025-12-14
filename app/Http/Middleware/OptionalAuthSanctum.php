<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthSanctum
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param  \Closure(Request): (\Illuminate\Http\Response|RedirectResponse)  $next
     * @return RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken()) {
            $user = Auth::guard('sanctum')->user();
            if (isset($user)) {
                Auth::setUser($user);
            }
        }

        return $next($request);
    }
}
