<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Security
{
    protected array $except = [
        'embed/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        foreach ($this->except as $except) {
            if (!$request->is($except)) {
                $response->headers->set('X-Frame-Options', 'SAMEORIGIN always');
            }
        }

        return $response;
    }
}
