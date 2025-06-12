<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RequirePassword
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return RedirectResponse
     */
    public function handle(Request $request, Closure $next): RedirectResponse
    {
        if(!Hash::check($request->get('current_password'), auth()->user()->password)){
            return back()->with("error", "Current Password doesn't match !");
        }

        return $next($request);
    }
}
