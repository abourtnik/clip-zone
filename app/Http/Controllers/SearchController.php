<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->get('q');

        if (!$q) {
            return view('pages.search', [
                'search' => $q,
                'results' => []
            ]);
        }

        $match = '%'.$q.'%';

        $videos = Video::active()
            ->where(function($query) use ($match) {
                $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match);
            })
            ->with('user')
            ->latest('publication_date')
            ->withCount('views')
            ->get();

        $users = User::active()
            ->where('username', 'LIKE' , $match)
            ->withCount([
                'subscribers',
                'videos' => fn($query) => $query->active()
            ])
            ->orderBy('subscribers_count', 'desc')
            ->get();

        return view('pages.search', [
            'search' => $q,
            'results' => $videos->concat($users)->shuffle()->paginate(12)->withQueryString(),
        ]);
    }

    public function search (Request $request): JsonResponse {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        $videos = Video::select(['id', 'title', 'thumbnail'])
            ->active()
            ->where(function($query) use ($match) {
                $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match);
            })
            ->latest('publication_date')
            ->get()
            ->map(fn($video) => [
                'category' => 'Video',
                'title' => $video->title,
                'url' => $video->route,
                'image' => $video->thumbnail_url,
            ]);

        $users = User::select(['id', 'username', 'avatar'])
            ->active()
            ->where('username', 'LIKE' , $match)
            ->withCount('subscribers')
            ->orderBy('subscribers_count', 'desc')
            ->get()
            ->map(fn($user) => [
                'category' => 'User',
                'title' => $user->username,
                'url' => $user->route,
                'image' => $user->avatar_url,
            ]);

        //dd($users->concat($videos));

        return response()->json([
            'total' => $users->concat($videos)->count(),
            'items' => $users->concat($videos)->slice(0, 15)->toArray(),
            'route' => route('search.index'). '?q=' .$q
        ]);

    }
}
