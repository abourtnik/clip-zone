<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Security
{
    protected array $exceptIframe = [
        'embed/*',
    ];

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Strict-Transport-Security', "max-age=31536000; includeSubDomains");

        foreach ($this->exceptIframe as $except) {
            if (!$request->is($except)) {
                $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            }
        }

        return $response;
    }
}
