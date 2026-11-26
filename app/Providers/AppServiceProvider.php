<?php

namespace App\Providers;

use App\Integrations\BrevoClient;
use App\Integrations\YoutubeClient;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
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
        $this->initIntegrationClient();
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
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    private function initIntegrationClient(): void
    {
        $this->app->singleton(YoutubeClient::class, fn() => new YoutubeClient(config('services.youtube.api_key')));
        $this->app->singleton(BrevoClient::class, fn() => new BrevoClient(config('services.brevo.api_key')));
    }
}
