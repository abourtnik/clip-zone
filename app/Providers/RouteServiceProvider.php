<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/profile';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot() : void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting() : void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
                ->response(fn(Request $request, array $headers) => $this->response($request, $headers));
        });

        RateLimiter::for('subscribe', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()->id . $request->user)
                ->response(fn(Request $request, array $headers) => $this->response($request, $headers));
        });

        RateLimiter::for('upload', function (Request $request) {
            return Limit::perMinute(130)->by($request->user()->id . $request->get('resumableIdentifier'))
                ->response(fn(Request $request, array $headers) => $this->response($request, $headers));
        });
    }

    private function response (Request $request, array $headers): JsonResponse {
        return response()->json([
            'message' => 'We have received too many requests, please wait before renewing your action'
        ], 429);
    }
}
