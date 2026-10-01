<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Route as RouteAlias;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    private const array IP_RATE_LIMITS = [
        'login'    => 5,
        'register' => 5,
        'contact'  => 3,
    ];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot() : void
    {
        $this->configureRateLimiting();

        $this->bindRoutes();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    private function configureRateLimiting() : void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
                ->response(fn(Request $request, array $headers) => $this->throttleResponse($request, $headers));
        });

        RateLimiter::for('subscribe', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()->id . $request->user)
                ->response(fn(Request $request, array $headers) => $this->throttleResponse($request, $headers));
        });

        RateLimiter::for('upload', function (Request $request) {
            return Limit::perMinute(130)->by($request->user()->id . $request->input('resumableIdentifier'))
                ->response(fn(Request $request, array $headers) => $this->throttleResponse($request, $headers));
        });

        foreach (self::IP_RATE_LIMITS as $route => $limit) {
            RateLimiter::for($route, function (Request $request) use ($limit) {
                return Limit::perMinute($limit)->by($request->ip())
                    ->response(fn(Request $request, array $headers) => $this->throttleResponse($request, $headers));
            });
        }
    }

    private function throttleResponse (Request $request, array $headers): JsonResponse|RedirectResponse {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('throttle.general')
            ], 429, $headers);
        }

        return back()
            ->with('error', __('throttle.general'))
            ->withInput();
    }

    private function bindRoutes(): void
    {
        Route::bind('reportable', function (string $value, RouteAlias $route) {

            $allowedTypes = ['video', 'comment', 'user'];

            $type = $route->parameter('type');

            if (!in_array($type, $allowedTypes)) {
                abort(404, "Invalid type: $type");
            }

            $modelClass = 'App\\Models\\' . Str::studly($type);

            if (!class_exists($modelClass)) {
                abort(404, "Model class $modelClass does not exist.");
            }

            return $modelClass::findOrFail($value);
        });
    }
}
