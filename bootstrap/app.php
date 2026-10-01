<?php

use App\Exceptions\SessionExpiredHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsBanned;
use App\Http\Middleware\IsPremium;
use App\Http\Middleware\Language;
use App\Http\Middleware\OptionalAuthSanctum;
use App\Http\Middleware\RateLimit;
use App\Http\Middleware\RequirePassword;
use App\Http\Middleware\Security;
use App\Http\Middleware\StripEmptyParams;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // WEB
        $middleware->web(append: [
            IsBanned::class,
            Security::class,
            Language::class,
            StripEmptyParams::class,
        ]);
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/profile');
        $middleware->preventRequestForgery(except: [
            'stripe-webhook',
        ]);

        // API
        $middleware->statefulApi();
        $middleware->api(append: [
            Language::class,
            OptionalAuthSanctum::class,
        ]);

        //ALIAS
        $middleware->alias([
            'admin' => IsAdmin::class,
            'premium' => IsPremium::class,
            'rate' => RateLimit::class,
            'password.confirm' => RequirePassword::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // GLOBAL
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
        $exceptions->context(fn () => [
            'url'        => request()->fullUrl(),
            'method'     => request()->method(),
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
            'input'      => request()->except(['current_password', 'password', 'password_confirmation', 'token']),
        ]);

        // CUSTOMS
        SessionExpiredHandler::register($exceptions);
    })
    ->withBroadcasting(__DIR__.'/../routes/channels.php')
    ->create();
