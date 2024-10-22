<?php

namespace App\Http\Controllers\User;

use App\Enums\PlaylistStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Playlist\StorePlaylistRequest;
use App\Http\Requests\Playlist\UpdatePlaylistRequest;
use App\Http\Resources\Playlist\PlaylistListResource;
use App\Http\Resources\Video\VideoListResource;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlaylistController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Playlist::class, 'playlist');
    }

    public function index(): View {
        return view('users.playlists.index', [
            'playlists' => Playlist::filter()
                ->where('user_id', Auth::user()->id)
                ->with('videos')
                ->withCount(['videos'])
                ->paginate(15)
                ->withQueryString()
        ]);
    }

    public function create(): View {
        return view('users.playlists.create', [
            'status' => PlaylistStatus::get(),
            'videos' => VideoListResource::collection(
                old('videos') ? Video::whereIn('id', old('videos'))
                    ->with('user')
                    ->with('views')
                    ->when(ctype_digit(implode('', old('videos'))), fn($q) => $q->orderByRaw('FIELD(id,' . implode(',', old('videos')).")"))
                    ->get() : []
            )->toJson()
        ]);
    }

    public function store(StorePlaylistRequest $request): RedirectResponse|PlaylistListResource {

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
            return new PlaylistListResource($playlist);
        }

        return redirect()->route('user.playlists.index');
    }

    public function edit(Playlist $playlist): View|RedirectResponse {

        return view('users.playlists.edit', [
            'playlist' => $playlist,
            'status' => PlaylistStatus::get(),
            'videos' => VideoListResource::collection(
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
}
