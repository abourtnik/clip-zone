<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plan;
use App\Models\Playlist;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageController
{
    public function home(Request $request): View {
        return view('pages.home', [
            'showPremiumModal' => $request->has('premium') && Auth::user()?->is_premium
        ]);
    }

    public function trend(): View {
        return view('pages.trend');
    }

    public function liked(): View {

        $interactions = Auth::user()
            ->interactions()
            ->whereHasMorph('likeable', [Video::class], fn($query) => $query->active())
            ->where('status', true)
            ->where('likeable_type', Video::class)
            ->with(['likeable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Video::class => ['user'],
                ]);
            }])
            ->latest('perform_at')
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->perform_at)->format('Y-m-d'))
            ->all();

        return view('pages.liked', [
            'data' => $interactions
        ]);
    }

    public function later(): View {

        $playlist = Auth::user()
            ->playlists()
            ->where('title', Playlist::WATCH_LATER_PLAYLIST)
            ->first();

        return view('playlists.show', [
            'playlist' => $playlist
                ->load([
                    'videos' => fn($q) => $q->with('user')
                ])
                ->loadCount([
                    'videos'
                ]),
        ]);
    }

    public function category(string $slug): View {

        $category = Category::where('slug', $slug)->firstOrFail();

        return view('pages.category', [
            'category' => $category->loadCount([
                'videos' => fn($query) => $query->active()
            ])
        ]);
    }

    public function terms (): View {
        return view('pages.terms');
    }

    public function premium (): View {
        return view('pages.premium', [
            'plans' => Plan::query()->orderBy('duration')->get()
        ]);
    }
}
