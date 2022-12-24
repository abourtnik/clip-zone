<?php

namespace App\Http\Controllers\User;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }

    public function index() {
        return view('users.videos.index', [
            'videos' => Auth::user()->load([
                'videos' => function ($query) {
                    $query->withCount(['likes', 'dislikes', 'interactions', 'comments', 'views'])
                        ->latest('updated_at');
                }

            ])->videos->paginate(15),
        ]);
    }

    public function create(): View {
        return view('users.videos.create', [
            'video_status' => VideoStatus::get(),
            'accepted_video_mimes_types' => implode(', ', VideoType::acceptedMimeTypes()),
            'accepted_video_formats' => implode(', ', VideoType::acceptedFormats()),
            'accepted_thumbnail_mimes_types' => implode(', ', ImageType::acceptedFormats())
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

    public function show(Video $video): View {
        return view('users.videos.show', [
            'video' => $video->loadCount([
                'views',
                'likes',
                'dislikes',
                'comments',
                'interactions'
            ]),
            'views' => $video->views()->select(
                DB::raw("(COUNT(*)) as count"),
                DB::raw("MONTHNAME(view_at) as month")
            )
                ->oldest('view_at')
                ->groupBy('month')
                ->get()
                ->toArray()
        ]);
    }

    public function edit(Video $video): View {
        return view('users.videos.edit', [
            'video' => $video,
            'video_status' => VideoStatus::get(),
            'accepted_thumbnail_mimes_types' => implode(', ', ImageType::acceptedFormats())
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

        $video->comments()->delete();
        $video->interactions()->delete();

        $video->delete();

        return redirect()->route('user.videos.index');
    }
}
