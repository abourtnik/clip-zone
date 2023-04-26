<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoController
{
    public function home () : ResourceCollection {
        return VideoResource::collection(
            Video::active()
                ->with('user')
                ->withCount('views')
                ->latest('publication_date')
                ->paginate(24)
        );
    }

    public function trend () : ResourceCollection {
        return VideoResource::collection(
            Video::active()
                ->with('user')
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->paginate(24)
        );
    }

    public function category (Category $category) : ResourceCollection {
        return VideoResource::collection(
            Video::active()
                ->whereRelation('category', 'id', $category->id)
                ->with('user')
                ->withCount('views')
                ->latest('publication_date')
                ->paginate(24)
        );
    }

    public function show (Video $video, Request $request) : View {

        $limit = ['unit' => 'minutes', 'value' => 1];

        $ip_views_count = $video->views()->where('ip' , $request->ip())
            ->whereBetween('view_at', [now()->sub($limit['unit'], 1), now()])
            ->limit($limit['value'])
            ->count();

        if ($ip_views_count < $limit['value']) {
            $video->views()->create([
                'ip' => $request->ip(),
                'user_id' => Auth::user()?->id
            ]);
        }

        return view('videos.show', [
            'video' => $video
                ->load([
                    'user' => fn($q) => $q->withCount('subscribers'),
                    'reports' => fn($q) => $q->where('user_id', Auth::id())
                ])
                ->loadCount([
                    'views',
                    'likes',
                    'dislikes',
                    'comments',
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'reports as reported_by_auth_user' => fn($q) => $q->where('user_id', Auth::id())
                ]),
            'videos' => Video::where('id', '!=', $video->id)
                ->active()
                ->with(['user'])
                ->withCount(['views'])
                ->inRandomOrder()
                ->limit(8)
                ->get(),
            'user_playlists' => Auth::user()?->load([
                'playlists' => fn($q) => $q->withCount([
                    'videos as has_video' => fn($q) => $q->where('video_id', $video->id)
                ])
            ])->playlists
        ]);
    }

    public function download (Video $video): StreamedResponse
    {
        return Storage::disk('videos')->download($video->file);
    }

    public function file (Video $video) : Response
    {
        return response()->noContent(200);
    }

    public function thumbnail (Video $video): Response
    {
        $file = Storage::disk('thumbnails')->get($video->thumbnail);

        return response($file)->header('Content-Type', Storage::disk('thumbnails')->mimeType($video->thumbnail));
    }

    public function user (User $user, Request $request) : ResourceCollection {

        $sort = $request->get('sort', 'recent');
        $excludePinned = $request->exists('excludePinned');

        return VideoResource::collection(
            $user->videos()
                ->active()
                ->when($excludePinned, fn($query) => $query->where('id', '!=', $user->pinned_video->id))
                ->withCount('views')
                ->with('user')
                ->when($sort === 'recent', fn($query) => $query->latest('created_at'))
                ->when($sort === 'popular', fn($query) => $query->orderByRaw('views_count DESC'))
                ->paginate(24)
        );
    }
}
