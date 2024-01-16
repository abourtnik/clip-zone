<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Blade::directive('size', function ($expression) {
            return "<?php echo \App\Helpers\Number::formatSizeUnits($expression) ?>";
        });

        Blade::directive('money', function ($centimes) {
            return "<?php echo number_format($centimes / 100, 2) . ' €' ?>";
        });
    }
}
