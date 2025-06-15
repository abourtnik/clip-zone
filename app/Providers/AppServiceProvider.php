<?php

namespace App\Providers;

use App\Services\BrevoService;
use App\Services\YoutubeService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        $this->app->singleton(YoutubeService::class, fn() => new YoutubeService(config('services.youtube.api_key')));
        $this->app->singleton(BrevoService::class, fn() => new BrevoService(config('services.brevo.api_key')));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() :void
    {
        // Pagination
        Paginator::useBootstrapFive();

        // Cashier
        Cashier::calculateTaxes();

        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
