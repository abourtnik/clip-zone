<?php

namespace App\Http\Controllers\User;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use App\Enums\Languages;
use App\Filters\VideoFilters;
use App\Helpers\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\Video\FileRequest;
use App\Http\Requests\Video\SaveRequest;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Models\Category;
use App\Models\Video;
use FFMpeg\FFProbe;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }

    public function index(VideoFilters $filters): View {
        return view('users.videos.index', [
            'videos' => Auth::user()->load([
                'videos' => function ($query) use ($filters) {
                    $query
                        ->filter($filters)
                        ->with('category:id,title')
                        ->withCount(['likes', 'dislikes', 'interactions', 'comments', 'views'])
                        ->latest('updated_at');
                }
            ])->videos->paginate(15)->withQueryString(),
            'status' => VideoStatus::getAll(),
            'filters' => $filters->receivedFilters(),
            'categories' => Category::all(),
        ]);
    }

    public function create(Video $video): View {
        return view('users.videos.create', [
            'video' => $video,
            'status' => VideoStatus::getActive(),
            'accepted_video_mimes_types' => implode(', ', VideoType::acceptedMimeTypes()),
            'accepted_video_formats' => implode(', ', VideoType::acceptedFormats()),
            'accepted_thumbnail_mimes_types' => implode(', ', ImageType::acceptedFormats()),
            'categories' => Category::all(),
            'languages' => Languages::get(),
            'playlists' => Auth::user()->playlists,
        ]);
    }

    public function store(StoreVideoRequest $request, Video $video): RedirectResponse {
        $validated = $request->safe()->merge([
            'thumbnail' =>  $request->file('thumbnail')->store('/', 'thumbnails'),
            'scheduled_date' => $request->get('scheduled_date'),
            'publication_date' => match((int) $request->get('status')) {
                VideoStatus::PUBLIC->value => now(),
                VideoStatus::PLANNED->value => $request->get('scheduled_date'),
                default => null,
            }
        ])->toArray();

        $video->update($validated);

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
                ->oldest('date')
                ->groupBy('date')
                ->get()
                ->toArray()
        ]);
    }

    public function edit(Video $video): View|RedirectResponse {

        if ($video->isDraft) {
            return redirect()->route('user.videos.create', $video);
        }

        return view('users.videos.edit', [
            'video' => $video,
            'status' => VideoStatus::getActive(),
            'accepted_thumbnail_mimes_types' => implode(', ', ImageType::acceptedFormats()),
            'categories' => Category::all(),
            'languages' => Languages::get(),
            'playlists' => Auth::user()->load([
                'playlists' => fn($q) => $q->withCount([
                    'videos as has_video' => fn($q) => $q->where('video_id', $video->id)
                ])
            ])->playlists
        ]);
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse {

        // Publication date is the first date that video become public, this data never be updated after first publication

        $validated = $request->safe()->merge([
            'thumbnail' => Image::storeAndDelete($request->file('thumbnail'), $video->thumbnail, 'thumbnails'),
            'scheduled_date' => match((int) $request->get('status')) {
                VideoStatus::PLANNED->value => $request->get('scheduled_date'),
                default => null,
            },
            'publication_date' => $video->publication_date?->isPast() ? $video->publication_date : match((int) $request->get('status')) {
                VideoStatus::PUBLIC->value => now(),
                VideoStatus::PLANNED->value => $request->get('scheduled_date'),
                default => null,
             }
        ])->toArray();

        $video->update($validated);

        $video->playlists()->sync($request->get('playlists'));

        if ($request->get('action') === 'save') {
            return redirect(route('user.videos.edit', $video));
        }

        return redirect()->route('user.videos.index');
    }

    public function destroy(Video $video): RedirectResponse {

        $video->delete();

        return redirect()->route('user.videos.index');
    }

    public function pin (Video $video) : RedirectResponse
    {
        $video->user->update([
            'pinned_video_id' => $video->id
        ]);

        return redirect()->route('user.videos.index');
    }

    public function unpin(Video $video): RedirectResponse
    {
        $video->user->update([
            'pinned_video_id' => null
        ]);

        return redirect()->route('user.videos.index');
    }

    public function upload (FileRequest $request, FileReceiver $receiver) : JsonResponse|UploadMissingFileException {

        if (!$receiver->isUploaded()) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {

            $file = $save->getFile();

            // Store file
            $name = $file->store('/','videos');

            // Remove chunk files
            //unlink($save->getFile()->getPathname());

            // Get file duration

            $ffprobe = FFProbe::create([
                'ffprobe.binaries' => Storage::disk('local')->path('bin/ffprobe')
            ]);

            $duration = $ffprobe
                ->format(Storage::disk('videos')->path($name))
                ->get('duration');

            $validated = $request->safe()->merge([
                'uuid' => (string) Str::uuid(),
                'title' => Str::replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName()),
                'original_file_name' => $file->getClientOriginalName(),
                'file' => $name,
                'mimetype' => $file->getMimeType(),
                'duration' => round($duration),
                'status' => VideoStatus::DRAFT,
            ])->toArray();

            $video = Auth::user()->videos()->create($validated);

            return response()->json([
                'route' => route('user.videos.create', $video)
            ]);
        }

        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone()
        ]);
    }

    public function save (SaveRequest $request): JsonResponse {

        $video = Video::findOrFail($request->get('video_id'));

        $playlists = $request->get('playlists', []);

        $video->playlists()->sync($playlists);

        return response()->json(null, 201);
    }
}
