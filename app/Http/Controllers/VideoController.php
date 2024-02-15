<?php

namespace App\Http\Controllers;

use App\Enums\VideoStatus;
use App\Events\Video\VideoViewed;
use App\Http\Resources\VideoResource;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Subtitle;
use App\Models\User;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\RedirectResponse;
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

    public function show (string $slug, Video $video, Request $request) : View|RedirectResponse {

        if ($video->slug !== $slug) {
            return redirect($video->route, 301);
        }

        $list = $request->query('list');

        $playlist = null;
        $currentIndex = null;

        if ($request->query('list')) {
            $playlist = Playlist::query()
                ->where('uuid', $list)
                ->where(function ($query) {
                    $query->active()
                        ->orWhere('user_id', Auth::id());
                })
                ->with([
                    'videos' => fn($query) => $query->with('user')->withPivot('position')
                ])
                ->withCount('videos')
                ->first();

            $currentIndex = $playlist?->videos->find($video->id)?->pivot->position;
        }

        event(new VideoViewed($video));

        $suggestedVideos = $this->videoService->getSuggestedVideos($video);

        return view('videos.show', [
            'video' => $video
                ->load([
                    'user' => fn($q) => $q->withCount('subscribers'),
                    'subtitles' => fn($q) => $q->public()->orderBy('name'),
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
            'nextVideo' => $this->videoService->getNextVideo($suggestedVideos, $playlist, $currentIndex),
            't' => $request->query('t', 0),
            'playlist' => $playlist,
            'currentIndex' => $currentIndex,
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

    public function subtitles (Video $video, Subtitle $subtitle): Response
    {
        $path = Subtitle::FILE_FOLDER.'/'.$subtitle->file;
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
