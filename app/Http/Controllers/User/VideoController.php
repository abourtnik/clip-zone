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
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Jobs\BuildFullFileFromChunks;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function create(Video $video): View|RedirectResponse {

        if (!$video->isDraft) {
            return redirect()->route('user.videos.edit', $video);
        }

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
            'thumbnail' =>  Image::storeAndDelete($request->file('thumbnail'), null, Video::THUMBNAIL_FOLDER),
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

       $created_at = $video->created_at->format('Y-m-d');
       $today = now()->subDay()->format('Y-m-d');

       DB::statement('SET @num = -1;');

       $views = DB::query()->selectRaw('DATE(dates.date) as date,  COUNT(views.id) as count')
        ->fromSub(function ($query) use ($created_at, $today) {
            $query->selectRaw("DATE_ADD('".$created_at."', interval @num := @num+1 day) AS date")->from('views')->havingRaw("DATE_ADD('".$created_at."', interval @num day) <= '".$today."'");
        }, 'dates')
        ->leftJoin('views as views', function($join) use ($video) {
            $join->on(DB::raw('dates.date'), '=', DB::raw('DATE(views.view_at)'))->where('views.video_id', $video->id);
        })
       ->groupBy('date')
       ->get()
       ->toArray();

        return view('users.videos.show', [
            'video' => $video->loadCount([
                'views',
                'likes',
                'dislikes',
                'comments',
                'interactions'
            ]),
            'views' => $views
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
            'thumbnail' => Image::storeAndDelete($request->file('thumbnail'), $video->thumbnail, Video::THUMBNAIL_FOLDER),
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

        $video->playlists()
            ->wherePivotIn('playlist_id', Auth::user()->playlists()->pluck('id')->toArray())
            ->sync($request->get('playlists'));

        if ($request->get('action') === 'save') {
            return redirect(route('user.videos.edit', $video));
        }

        return redirect()->route('user.videos.index');
    }

    public function destroy(Video $video): RedirectResponse {

        $video->delete();

        return redirect()->back();
    }

    public function pin (Video $video) : RedirectResponse
    {
        $video->user->update([
            'pinned_video_id' => $video->id
        ]);

        return redirect()->back();
    }

    public function unpin(Video $video): RedirectResponse
    {
        $video->user->update([
            'pinned_video_id' => null
        ]);

        return redirect()->back();
    }

    public function upload (FileRequest $request): JsonResponse {

        $folder = $request->get('resumableIdentifier');

        $chunk = $request->file('file');

        $name = $request->get('resumableChunkNumber'). ".part";

        $chunk->storeAs(Video::CHUNK_FOLDER.'/'.$folder, $name, 'local');

        if ($request->get('resumableTotalChunks') === $request->get('resumableChunkNumber')) {

            $video = Video::create([
                'uuid' => (string) Str::uuid(),
                'title' => Str::replace('.'.$chunk->getClientOriginalExtension(), '', $chunk->getClientOriginalName()),
                'original_file_name' => $chunk->getClientOriginalName(),
                'mimetype' => $request->get('resumableType'),
                'size' => $request->get('resumableTotalSize'),
                'status' => VideoStatus::DRAFT,
                'user_id' => Auth::user()->id
            ]);

            BuildFullFileFromChunks::dispatch($folder, $video, $chunk->getClientOriginalExtension());

            return response()->json([
                'route' => route('user.videos.create', $video)
            ]);
        }

        $percentage = ceil($request->get('resumableChunkNumber') / $request->get('resumableTotalChunks') * 100);

        return response()->json([
            "done" => $percentage
        ]);
    }
}
