<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
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
        Paginator::useBootstrapFive();

        Queue::after(function (JobProcessed $event) {

            /*
            Log::debug('An informational message.');
            Log::debug($event->connectionName);
            Log::debug($event->job->getName());
            Log::debug($event->job->timeout());
            Log::debug($event->job->resolveName());
            Log::debug($event->job->uuid());
            Log::debug($event->job->getRawBody());
            */

            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });

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

        View::composer(['pages.*', 'auth.*', 'videos.show'], function($view) {
            $view->with('categories', Category::where('in_menu', true)->ordered()->get());
            $view->with('subscriptions', Auth::user()?->subscriptions()->latest('subscribe_at')->get());
        });
    }
}
