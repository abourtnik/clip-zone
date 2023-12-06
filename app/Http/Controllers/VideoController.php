<?php

namespace App\Http\Controllers;

use App\Enums\VideoStatus;
use App\Http\Resources\VideoResource;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoController
{
    public function __construct(private readonly VideoService $videoService)
    {
    }

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

        $suggestedVideos = $this->videoService->getSuggestedVideos($video);

        return view('videos.show', [
            'video' => $video
                ->load([
                    'user' => fn($q) => $q->withCount('subscribers'),
                    'reportByAuthUser'
                ])
                ->loadCount([
                    'views',
                    'likes',
                    'dislikes',
                    'comments',
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                ]),
            'videos' => $suggestedVideos,
            'nextVideoUrl' => $suggestedVideos->count() ? route('video.show', $suggestedVideos->random()) : null,
            't' => $request->query('t', 0),
        ]);
    }

    public function download (Video $video): StreamedResponse
    {
        return Storage::download(Video::VIDEO_FOLDER .'/'. $video->file);
    }

    public function file (Video $video) : void {}

    public function thumbnail (Video $video): Response
    {
        $path = Video::THUMBNAIL_FOLDER.'/'.$video->thumbnail;
        $file = Storage::get($path);

        return response($file)->header('Content-Type', Storage::mimeType($path));
    }

    public function embed (Video $video): View
    {
        return view(match ($video->real_status) {
            VideoStatus::PUBLIC => 'videos.embed.public',
            VideoStatus::UNLISTED => 'videos.embed.public',
            VideoStatus::PRIVATE => 'videos.embed.private',
            VideoStatus::BANNED => 'videos.embed.banned',
            default => 'videos.embed.missing'
        },[
            'video' => $video
        ]);
    }

    public function user (User $user, Request $request) : ResourceCollection {

        $sort = $request->get('sort', 'latest');
        $excludePinned = $request->exists('excludePinned');

        return VideoResource::collection(
            $user->videos()
                ->active()
                ->when($excludePinned, fn($query) => $query->where('id', '!=', $user->pinned_video->id))
                ->withCount('views')
                ->with('user')
                ->when($sort === 'latest', fn($query) => $query->latest('publication_date'))
                ->when($sort === 'popular', fn($query) => $query->orderByRaw('views_count DESC'))
                ->when($sort === 'oldest', fn($query) => $query->oldest('publication_date'))
                ->paginate(24)
        );
    }
}
