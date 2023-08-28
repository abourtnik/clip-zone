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
        if(Auth::user()?->premium_subscription){
            return redirect()
                ->route('pages.premium')
                ->with('error', "You already have an subscription, you can manage it on your <a href=".route('user.edit').">account</a>.");
        }

        return $next($request);
    }
}
