<?php

namespace App\Http\Controllers;

use App\Enums\VideoStatus;
use App\Filters\SearchFilters;
use App\Http\Resources\VideoResource;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request, SearchFilters $filters): View
    {
        list('q' => $q, 'type' => $type) = $request->only('q', 'type');

        if (!$q) {
            return view('pages.search', [
                'search' => $q,
                'results' => collect()
            ]);
        }

        $match = '%'.$q.'%';

        $videos = in_array($type, ['videos', null]) ? Video::active()
            ->filter($filters)
            ->where(function($query) use ($match) {
                $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match);
            })
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

        $videos = Video::select(['id', 'uuid', 'title', 'thumbnail'])
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
                'image' => $user->avatar_url
            ]);

        return response()->json([
            'total' => $users->concat($videos)->count(),
            'items' => $users->concat($videos)->slice(0, 15)->toArray(),
            'route' => route('search.index'). '?q=' .$q,
        ]);

    }

    public function searchVideos (Request $request): ResourceCollection {

        list('q' => $q, 'except_ids' => $except_ids) = $request->only('q', 'except_ids');

        $match = '%'.$q.'%';

        return VideoResource::collection(
            Video::where(fn($q) => $q->active()->orWhere(fn($q) => $q->where('user_id' , Auth::user()->id))->whereNotIn('status', [VideoStatus::DRAFT, VideoStatus::BANNED]))
                ->whereNotIn('id', $except_ids)
                ->where(fn($query) => $query->where('title', 'LIKE', $match)->orWhere('description', 'LIKE', $match))
                ->with('user')
                ->withCount('views')
                ->latest('publication_date')
                ->limit(24)
                ->get()
        );
    }
}