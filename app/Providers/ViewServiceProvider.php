<?php

namespace App\Providers;

use App\View\Composers\FilterComposer;
use App\View\Composers\HeaderComposer;
use App\View\Composers\ReportComposer;
use App\View\Composers\SidebarComposer;
use App\View\Composers\UploadComposer;
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
        View::composer('forms.fields.autocomplete', FilterComposer::class);

        View::composer('layouts.menus.sidebars.front', SidebarComposer::class);

        View::composer('layouts.menus.header', HeaderComposer::class);

        View::composer('modals.report', ReportComposer::class);

        View::composer('users.videos.modals.upload', UploadComposer::class);
    }
}
