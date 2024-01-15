<?php

namespace App\Providers;

use App\View\Composers\FilterComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Using class based composers...
        View::composer([
            'admin.videos.index',
            'admin.comments.index',
            'admin.reports.index',
            'users.comments.index'
        ], FilterComposer::class);
    }
}
