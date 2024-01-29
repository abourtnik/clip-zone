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
            return "<?php echo \Illuminate\Support\Number::fileSize($expression) ?>";
        });

        Blade::directive('money', function ($centimes) {
            return "<?php echo \Illuminate\Support\Number::currency($centimes / 100, in: 'EUR', locale: 'fr') ?>";
        });

        Blade::directive('abbreviate', function ($number) {
            return "<?php echo \Illuminate\Support\Number::abbreviate($number, maxPrecision: 1) ?>";
        });
    }
}
