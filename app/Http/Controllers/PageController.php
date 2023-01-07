<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController
{
    public function home(): View {
        return view('pages.home');
    }

    public function trend(): View {
        return view('pages.trend');
    }

    public function category(string $slug): View {

        $category = Category::where('slug', $slug)->firstOrFail();

        return view('pages.category', [
            'category' => $category->loadCount('videos')
        ]);
    }

    public function subscriptions(): View {

        $users = [];
        $subscriptions = Auth::user()->subscriptions;

        if (!$subscriptions->count()) {
            $users = User::where('id', '!=', Auth::user()->id)
                ->where('show_subscribers', true)
                ->withCount('subscribers')
                ->orderBy('subscribers_count', 'desc')
                ->paginate(18);
        } else {

            $sorted_videos = Auth::user()
                ->subscriptions_videos()
                ->latest()
                ->get()
                ->groupBy(fn ($item) => Carbon::parse($item->created_at)->format('Y-m-d'))
                ->all();

        }

        return view('pages.subscriptions', [
            'subscriptions' => $subscriptions,
            'users' => $users,
            'sorted_videos' => $sorted_videos
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

    public function search(Request $request): View {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        $videos = Video::active()
            ->where(fn($query) =>
                $query->where('title', 'LIKE', $match)
                    ->orWhere('description', 'LIKE', $match)
                    ->OrWhereHas('user', fn($query) => $query->where('username', 'LIKE', $match))
            )
            ->latest('publication_date')
            ->paginate(12);

        return view('pages.search', [
            'search' => $q,
            'videos' => $videos
        ]);
    }

    public function searchResult (Request $request): JsonResponse {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        $results = Video::active()
            ->where(fn($query) =>
                $query->where('title', 'LIKE', $match)
                    ->orWhere('description', 'LIKE', $match)
                    //->OrWhereHas('user', fn($query) => $query->where('username', 'LIKE', $match))
                )
            //->latest('publication_date')
            ->limit(14)
            ->pluck('title')
            ->toArray();

        return response()->json($results);

    }
}
