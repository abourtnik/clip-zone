<?php

namespace App\Providers;

use App\Logs\CronLog;
use Opcodes\LogViewer\Facades\LogViewer;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
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
        LogViewer::auth(function ($request) {
            return $request->user() && $request->user()->is_admin;
        });

        LogViewer::extend('cron', CronLog::class);
    }
}
