<?php

namespace App\Providers;

use App\Enums\ReportReason;
use App\Http\Resources\NotificationResource;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use App\Notifications\Channels\DatabaseChannel;

use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        //
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

        // SIDEBAR
        View::composer([
            'layouts.menus.sidebars.*',
            'subscription.index',
        ], function($view) {
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
            $view->with('favorite_playlists', Auth::user()?->favorites_playlist()->latest('added_at')->get());
        });

        // NOTIFICATIONS
        View::composer('layouts.menus.header', function($view) {
            $view->with('unread_notifications', Auth::user()?->notifications()->unread()->count());
        });

        View::composer('components.layout', function($view) {
            if (!Str::contains($view->getName(), ['partials', 'modals', 'types'])) {
                $view->with(
                    'json_notifications',
                    NotificationResource::collection(
                        Auth::user()?->notifications()->latest()->limit(20)->get() ?? []
                    )->toJson(),
                );
            }
        });

        // Reports
        View::composer('modals.report', function($view) {
            $view->with('report_reasons', ReportReason::get());
        });

    }
}
