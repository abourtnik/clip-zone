<?php

namespace App\Providers;

use App\Services\YoutubeService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use App\Notifications\Channels\DatabaseChannel;

use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        Cashier::ignoreMigrations();

        $this->app->singleton(YoutubeService::class, fn() => new YoutubeService(config('services.youtube.api_key')));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() :void
    {
        // Notifications
        $this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel());

        // Pagination
        Paginator::useBootstrapFive();

        // Collection Pagination
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Cashier::calculateTaxes();
    }
}
