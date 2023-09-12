<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Security
{
    protected array $exceptIframe = [
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

        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Content-Security-Policy', "default-src 'self' http: https: data: blob: 'unsafe-inline'");
        $response->headers->set('Strict-Transport-Security', "max-age=31536000; includeSubDomains");

        foreach ($this->exceptIframe as $except) {
            if (!$request->is($except)) {
                $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            }
        }

        return $response;
    }
}
