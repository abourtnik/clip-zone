<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IsPremium
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if(!Auth::user()->is_premium){
            return redirect()
                ->route('pages.premium');
        }

        return $next($request);
    }
}
