<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class RequirePassword
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next):  Response
    {
        if(!Hash::check($request->string('current_password'), auth()->user()->password)){
            return back()->with("error", __("Current Password doesn't match !"));
        }

        return $next($request);
    }
}
