<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageController
{
    public function home(): View {
        return view('pages.home');
    }

    public function trend(): View {
        return view('pages.trend');
    }

    public function liked(): View {

        $interactions = Auth::user()
            ->interactions()
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

        return view('pages.liked');
    }

    public function category(string $slug): View {

        $category = Category::where('slug', $slug)->firstOrFail();

        return view('pages.category', [
            'category' => $category->loadCount([
                'videos' => fn($query) => $query->active()
            ])
        ]);
    }

    public function user(User $user): View {
        return view('pages.user', [
            'user' => $user->load([
                'videos' => function ($q) {
                    $q->with('user')
                        ->withCount('views')
                        ->active()
                        ->latest('publication_date');
                }
            ])->loadCount('subscribers', 'videos_views')
        ]);
    }
}
