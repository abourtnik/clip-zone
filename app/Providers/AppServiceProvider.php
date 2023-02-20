<?php

namespace App\Providers;

use App\Enums\ReportReason;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
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

        // Menu Request
        View::composer(['pages.*', 'auth.*', 'videos.show', 'users.show', 'subscription.*', 'contact.show', 'errors::*'], function($view) {
            $view->with('categories', Category::where('in_menu', true)->ordered()->get());
            $view->with(
                'subscriptions',
                Auth::user()?->subscriptions()
                    ->withCount([
                        'videos as new_videos' => fn($query) => $query->active()
                            ->where('publication_date', '>', DB::raw('subscriptions.read_at')),
                        'subscribers'
                    ])
                    ->latest('subscribe_at')
                    ->get()
            );
            $view->with('report_reasons', ReportReason::get());
        });
    }
}
