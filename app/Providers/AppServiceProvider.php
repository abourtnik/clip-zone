<?php

namespace App\Providers;

use App\Services\YoutubeService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
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

        // Cashier
        Cashier::calculateTaxes();

        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
