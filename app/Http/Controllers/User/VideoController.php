<?php

namespace App\Http\Controllers\User;

use App\Enums\ThumbnailType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }

    public function index(): View {
        return view('users.videos.index', [
            'videos' => Auth::user()->videos()->latest('updated_at')->paginate(15)
        ]);
    }

    public function create(): View {
        return view('users.videos.create', [
            'status' => VideoStatus::get(),
            'accepted_video_mimes_types' => implode(', ', VideoType::acceptedMimeTypes()),
            'accepted_video_formats' => implode(', ', VideoType::acceptedFormats()),
            'accepted_thumbnail_mimes_types' => implode(', ', ThumbnailType::acceptedFormats())
        ]);
    }

    public function store(StoreVideoRequest $request): RedirectResponse {

        $validated = $request->safe()->merge([
            'file' => $request->file('file')->store('/', 'videos'),
            'mimetype' =>$request->file('file')->getMimeType(),
            'duration' =>  floor((new \getID3())->analyze($request->file('file')->getRealPath())['playtime_seconds']),
            'thumbnail' =>  $request->file('thumbnail')->store('/', 'thumbnails')
        ])->toArray();

        Auth::user()->videos()->create($validated);

        if ($request->get('action') === 'create') {
            return redirect(route('user.videos.create'));
        }

        return redirect()->route('user.videos.index');
    }

    public function edit(Video $video): View {
        return view('users.videos.edit', [
            'video' => $video,
            'status' => VideoStatus::get(),
            'accepted_thumbnail_mimes_types' => implode(', ', ThumbnailType::acceptedFormats())
        ]);
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse {

        $validated = $request->safe()->merge([
            'thumbnail' =>  $request->file('thumbnail')?->store('/', 'thumbnails') ?? $video->thumbnail
        ])->toArray();

        $video->update($validated);

        if ($request->get('action') === 'save') {
            return redirect(route('user.videos.edit', $video));
        }

        return redirect()->route('user.videos.index');
    }

    public function destroy(Video $video): RedirectResponse {

        $video->delete();

        return redirect()->route('user.videos.index');
    }
}
