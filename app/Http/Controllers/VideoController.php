<?php

namespace App\Http\Controllers;

use App\Actions\Video\ShowVideoAction;
use App\Enums\VideoStatus;
use App\Models\Subtitle;
use App\Models\Thumbnail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function show (string $slug, Video $video, ShowVideoAction $showVideoAction) : View|RedirectResponse {

        if ($video->slug !== $slug) {
            return redirect($video->route, 301);
        }

        return view('videos.show', $showVideoAction->data($video));
    }

    public function download(Video $video): RedirectResponse
    {
        $url = Storage::temporaryUrl(
            $video->path,
            now()->addMinutes(5),
            [
                'ResponseContentDisposition' => 'attachment; filename="' . $video->slug . '.mp4"',
            ]
        );

        return redirect()->away($url);
    }

    public function file (Video $video) : RedirectResponse
    {
        $expiration = now()->addSeconds($video->getRawOriginal('duration') + 300);

        $url = Storage::temporaryUrl($video->path, $expiration);

        return redirect()->away($url);
    }

    public function thumbnail (Video $video): RedirectResponse
    {
        $url = Storage::temporaryUrl(
            Video::THUMBNAIL_FOLDER.'/'.$video->thumbnail->file,
            now()->addMinutes(5),
            ['ResponseCacheControl' => 'private, max-age=300'],
        );

        return redirect($url)->header('Cache-Control', 'private, max-age=240');
    }

    public function thumbnails (Video $video, Thumbnail $thumbnail): RedirectResponse
    {
        $url = Storage::temporaryUrl(
            Video::THUMBNAIL_FOLDER.'/'.$thumbnail->file,
            now()->addMinutes(5),
            ['ResponseCacheControl' => 'private, max-age=300'],
        );

        return redirect($url)->header('Cache-Control', 'private, max-age=240');
    }

    public function subtitles (Request $request, Video $video, Subtitle $subtitle): StreamedResponse
    {
        $path = Subtitle::FILE_FOLDER . '/' . $subtitle->file;

        abort_unless(Storage::exists($path), 404);

        $headers = [
            'Content-Type'  => 'text/vtt; charset=utf-8',
            'Cache-Control' => 'private, max-age=240',
        ];

        $filename = $subtitle->name . '.vtt';

        return $request->boolean('download')
            ? Storage::download($path, $filename, $headers)
            : Storage::response($path, $filename, $headers);
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
