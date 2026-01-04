<?php

namespace App\Http\Controllers;

use App\Actions\Video\ShowVideoAction;
use App\Enums\VideoStatus;
use App\Models\Subtitle;
use App\Models\Thumbnail;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function show (string $slug, Video $video, ShowVideoAction $showVideoAction) : View|RedirectResponse {

        if ($video->slug !== $slug) {
            return redirect($video->route, 301);
        }

        return view('videos.show', $showVideoAction->data($video));
    }

    public function download (Video $video): RedirectResponse
    {
        return redirect()->route('video.file', $video);
    }

    public function file (Video $video) : Response
    {
        return response()->noContent(200);
    }

    public function thumbnail (Video $video): Response
    {
        $path = Video::THUMBNAIL_FOLDER.'/'.$video->thumbnail->file;
        $file = Storage::get($path);

        return response($file)->header('Content-Type', Storage::mimeType($path));
    }

    public function thumbnails (Video $video, Thumbnail $thumbnail): Response
    {
        $path = Video::THUMBNAIL_FOLDER.'/'.$thumbnail->file;
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
            VideoStatus::PUBLIC, VideoStatus::UNLISTED => 'videos.embed.public',
            VideoStatus::PRIVATE => 'videos.embed.private',
            VideoStatus::BANNED => 'videos.embed.banned',
            default => 'videos.embed.missing'
        },[
            'video' => $video
        ]);
    }
}
