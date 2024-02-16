<?php

namespace App\Http\Controllers\User;

use App\Enums\PlaylistStatus;
use App\Filters\PlaylistFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Playlist\StorePlaylistRequest;
use App\Http\Requests\Playlist\UpdatePlaylistRequest;
use App\Http\Requests\Video\SaveRequest;
use App\Http\Resources\PlaylistResource;
use App\Http\Resources\VideoResource;
use App\Models\Pivots\PlaylistVideo;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlaylistController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Playlist::class, 'playlist');
    }

    public function index(PlaylistFilters $filters): View {
        return view('users.playlists.index', [
            'playlists' => Playlist::where('user_id', Auth::user()->id)
                ->withCount(['videos'])
                ->paginate(15)
                ->withQueryString(),
            'status' => PlaylistStatus::get(),
            'filters' => $filters->receivedFilters(),
        ]);
    }

    public function list(Video $video): ResourceCollection {
        return PlaylistResource::collection(
            Playlist::where('user_id', Auth::user()->id)
                ->withCount([
                    'videos as has_video' => fn($q) => $q->where('video_id', $video->id)
                ])
                ->paginate(20)
        );
    }

    public function create(): View {
        return view('users.playlists.create', [
            'status' => PlaylistStatus::get(),
            'videos' => VideoResource::collection(
                old('videos') ? Video::whereIn('id', old('videos'))
                    ->with('user')
                    ->with('views')
                    ->when(ctype_digit(implode('', old('videos'))), fn($q) => $q->orderByRaw('FIELD(id,' . implode(',', old('videos')).")"))
                    ->get() : []
            )->toJson()
        ]);
    }

    public function store(StorePlaylistRequest $request): RedirectResponse|PlaylistResource {

        $validated = $request->safe()->merge([
            'uuid' => (string) Str::uuid(),
            'user_id' => Auth::user()->id
        ])->toArray();

        $videos = $request->get('videos', []);

        $playlist = Playlist::create($validated);

        foreach ($videos as $key => $id) {
            $playlist->videos()->attach([
                $id => ['position' => $key]
            ]);
        }

        if ($request->ajax()) {
            return new PlaylistResource($playlist);
        }

        return redirect()->route('user.playlists.index');
    }

    public function edit(Playlist $playlist): View|RedirectResponse {

        return view('users.playlists.edit', [
            'playlist' => $playlist,
            'status' => PlaylistStatus::get(),
            'videos' => VideoResource::collection(
                old('videos') ? Video::whereIn('id', old('videos'))
                    ->with('user')
                    ->with('views')
                    ->when(ctype_digit(implode('', old('videos'))), fn($q) => $q->orderByRaw('FIELD(id,' . implode(',', old('videos')).")"))
                    ->get() : $playlist->videos->load('user')->loadCount('views')
            )->toJson()
        ]);
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist): RedirectResponse {

        $validated = $request->validated();

        $videos = [];

        foreach ($request->get('videos', []) as $key => $value) {
            $videos[$value] = ['position' => $key];
        }

        $playlist->videos()->sync($videos);

        $playlist->update($validated);

        $playlist->touch();

        if ($request->get('action') === 'save') {
            return redirect(route('user.playlists.edit', $playlist));
        }

        return redirect()->route('user.playlists.index');
    }

    public function destroy(Playlist $playlist): RedirectResponse {

        $playlist->delete();

        return redirect()->route('user.playlists.index');
    }

    public function save (SaveRequest $request): JsonResponse {

        $video = Video::findOrFail($request->get('video_id'));

        $playlists = $request->get('playlists', []);

        foreach ($playlists as $playlist) {

            if ($playlist['checked']) {

                $exist = PlaylistVideo::where([
                    'playlist_id' => $playlist['id'],
                    'video_id' => $video->id
                ])->exists();

                if (!$exist) {

                    $lastPosition = PlaylistVideo::where('playlist_id', $playlist['id'])
                        ->latest('position')
                        ->first()
                        ?->position;

                    PlaylistVideo::create([
                        'playlist_id' => $playlist['id'],
                        'video_id' => $video->id,
                        'position' => is_null($lastPosition) ? 0 : $lastPosition + 1
                    ]);
                }

            }

            else {
                PlaylistVideo::where([
                    'playlist_id' => $playlist['id'],
                    'video_id' => $video->id
                ])->delete();
            }
        }

        return response()->json(null, 201);
    }

}
