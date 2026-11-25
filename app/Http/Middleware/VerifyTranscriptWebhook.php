<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTranscriptWebhook
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $driver = config('transcription.default');

        $expected = config('transcription.providers.'.$driver.'.webhook_secret');
        $received = (string) $request->header(config('transcription.webhook_auth_header_name'));

        abort_unless(hash_equals($expected, $received), 403, 'Invalid webhook secret.');

        return $next($request);
    }
}
