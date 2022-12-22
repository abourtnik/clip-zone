<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController
{
    public function home(): View {
        return view('pages.home', [
            'videos' => Video::active()->with('user')->latest('publication_date')->paginate(12)
        ]);
    }

    public function trend(): View {
        return view('pages.trend', [
            'videos' => Video::active()->latest('publication_date')->paginate(12)
        ]);
    }

    public function subscriptions(): View {
        return view('pages.subscriptions', [
            'subscriptions' => auth()->user()->subscriptions
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
