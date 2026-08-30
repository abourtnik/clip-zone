<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
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
            'categories' => $this->getCategories(),
            'show_sidebar' => $this->showSidebar(),
        ]);

        if (Auth::user()) {
            $view->with([
                'subscriptions' => $this->getAuthUserSubscriptions(),
                'favorite_playlists' => $this->getAuthUserFavoritePlaylists()
            ]);
        }
    }

    private function getCategories(): Collection
    {
        return Cache::rememberForever('categories', function () {
            return Category::query()
                ->where('in_menu', true)
                ->ordered()
                ->get();
        });
    }

    private function showSidebar(): bool
    {
        return !in_array(request()->route()?->getName(), ['video.show', 'pages.premium']);
    }

    private function getAuthUserSubscriptions(): Collection
    {
        return Auth::user()
                ->subscriptions()
                ->withExists([
                    'videos as has_new_video' => function($query) {
                        return $query
                            ->active()
                            ->where('published_at', '>', DB::raw('subscriptions.read_at'));
                    }
                ])
                ->latest('subscribe_at')
                ->get();
    }

    private function getAuthUserFavoritePlaylists(): Collection
    {
        return Auth::user()
            ->favorites_playlist()
            ->latest('added_at')
            ->get();
    }
}
