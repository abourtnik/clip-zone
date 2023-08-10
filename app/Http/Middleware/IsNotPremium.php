<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsNotPremium
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()?->is_premium){
            return redirect()
                ->route('pages.premium')
                ->with('error', 'You already have an active subscription until <strong>' .Auth::user()->premium_end->format('d F Y').'</strong>');
        }

        return $next($request);
    }
}
