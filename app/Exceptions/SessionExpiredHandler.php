<?php

namespace App\Exceptions;

use App\Helpers\Locale;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Configuration\Exceptions;
use Symfony\Component\HttpFoundation\Response;

class SessionExpiredHandler
{
    public static function register(Exceptions $exceptions): void
    {
        $exceptions->render(fn (AuthenticationException $exception, Request $request) =>
            self::handle($request, Response::HTTP_UNAUTHORIZED)
        );

        $exceptions->render(fn (TokenMismatchException $exception, Request $request) =>
            self::handle($request, 419)
        );
    }

    private static function handle(Request $request, int $status): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('auth.session_expired', [], Locale::resolve($request)),
            ], $status);
        }

        return redirect()
            ->guest(route('login'))
            ->with('error', __('auth.session_expired'));
    }
}
