<?php

namespace App\Http\Controllers\User;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use App\Filters\VideoFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Languages;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }

    public function index(VideoFilters $filters) {
        return view('users.videos.index', [
            'videos' => Auth::user()->load([
                'videos' => function ($query) use ($filters) {
                    $query
                        ->filter($filters)
                        ->withCount(['likes', 'dislikes', 'interactions', 'comments', 'views'])
                        ->latest('updated_at');
                }
            ])->videos->paginate(15)->withQueryString(),
            'video_status' => VideoStatus::get(),
            'filters' => $filters->receivedFilters(),
            'categories' => Category::all(),
        ]);
    }

    public function create(): View {
        return view('users.videos.create', [
            'video_status' => VideoStatus::get(),
            'accepted_video_mimes_types' => implode(', ', VideoType::acceptedMimeTypes()),
            'accepted_video_formats' => implode(', ', VideoType::acceptedFormats()),
            'accepted_thumbnail_mimes_types' => implode(', ', ImageType::acceptedFormats()),
            'categories' => Category::all(),
            'languages' => Languages::getNames(),
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
                DB::raw("DATE(view_at) as date")
            )
                ->oldest('view_at')
                ->groupBy('date')
                ->get()
                ->toArray()
        ]);
    }

    public function edit(Video $video): View {
        return view('users.videos.edit', [
            'video' => $video,
            'video_status' => VideoStatus::get(),
            'accepted_thumbnail_mimes_types' => implode(', ', ImageType::acceptedFormats()),
            'categories' => Category::all(),
            'languages' => Languages::getNames(),
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
