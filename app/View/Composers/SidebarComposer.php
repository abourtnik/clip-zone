<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'categories' => Cache::rememberForever('categories', fn() => Category::query()->where('in_menu', true)->ordered()->get()),
            'show_sidebar' => !in_array(request()->route()?->getName(), ['video.show', 'pages.premium']),
        ]);

        if (Auth::user()) {
            $view->with([
                'subscriptions' => Auth::user()
                    ->subscriptions()
                    ->withCount([
                        'videos as new_videos' => function($query) {
                            return $query->active()->where('publication_date', '>', DB::raw('subscriptions.read_at'));
                        }
                    ])
                    ->latest('subscribe_at')
                    ->get(),
                'favorite_playlists' => Auth::user()
                    ->favorites_playlist()
                    ->latest('added_at')
                    ->get()
            ]);
        }
    }
}
