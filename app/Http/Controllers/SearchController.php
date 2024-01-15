<?php

namespace App\Http\Controllers;

use App\Filters\SearchFilters;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request, SearchFilters $filters): View
    {
        $q = $request->get('q');
        $type = $request->get('type');

        if (!$q) {
            return view('pages.search', [
                'search' => $q,
                'results' => collect()
            ]);
        }

        $match = '%'.$q.'%';

        $videos = in_array($type, ['videos', null]) ? Video::active()
            ->filter($filters)
            ->where(
                fn($query) => $query
                    ->where('title', 'LIKE', $match)
                    ->orWhere('description', 'LIKE', $match)
                    ->orWhereRelation('user', 'username', 'LIKE', $match)
            )
            ->with('user')
            ->latest('publication_date')
            ->withCount('views')
            ->get() : collect();

        $users = in_array($type, ['users', null]) ? User::active()
            ->where('username', 'LIKE' , $match)
            ->withCount([
                'subscribers',
                'videos' => fn($query) => $query->active()
            ])
            ->orderBy('subscribers_count', 'desc')
            ->get() : collect();

        $playlists = in_array($type, ['playlists', null]) ? Playlist::active()
            ->where('title', 'LIKE' , $match)
            ->withCount('videos')
            ->having('videos_count', '>', 0)
            ->latest('created_at')
            ->get() : collect();

        return view('pages.search', [
            'search' => $q,
            'results' => $videos->concat($users)->concat($playlists)->shuffle()->paginate(12)->withQueryString(),
            'filters' => $filters->receivedFilters() + ($type ? ['type' => $type] : [])
        ]);
    }

    public function search (Request $request): JsonResponse {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        $videos = Video::select(['id', 'uuid', 'title'])
            ->active()
            ->where(function($query) use ($match) {
                $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match);
            })
            ->latest('publication_date')
            ->get()
            ->map(fn($video) => [
                'category' => 'Video',
                'title' => Str::limit($video->title, 68),
                'url' => $video->route,
            ]);

        $users = User::select(['id', 'username'])
            ->active()
            ->where('username', 'LIKE' , $match)
            ->withCount('subscribers')
            ->orderBy('subscribers_count', 'desc')
            ->get()
            ->map(fn($user) => [
                'category' => 'User',
                'title' => $user->username,
                'url' => $user->route,
            ]);

        $playlists = Playlist::select(['id', 'uuid', 'title'])
            ->active()
            ->where('title', 'LIKE' , $match)
            ->withCount('videos')
            ->having('videos_count', '>', 0)
            ->orderBy('videos_count', 'desc')
            ->get()
            ->map(fn($playlist) => [
                'category' => 'Playlist',
                'title' => Str::limit($playlist->title, 68),
                'url' => $playlist->route,
            ]);

        return response()->json([
            'total' => $users->concat($videos)->concat($playlists)->count(),
            'items' => $users->concat($videos)->concat($playlists)->slice(0, 15)->toArray(),
            'route' => route('search.index'). '?q=' .$q,
        ]);

    }
}
