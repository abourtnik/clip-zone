<?php

namespace App\Http\Controllers\User;

use App\Enums\PlaylistStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Playlist\StorePlaylistRequest;
use App\Http\Requests\Playlist\UpdatePlaylistRequest;
use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
                ->latest('created_at')
                ->paginate(15)
                ->withQueryString()
        ]);
    }

    public function create(): View {
        return view('users.playlists.create', [
            'status' => PlaylistStatus::get()
        ]);
    }

    public function store(StorePlaylistRequest $request): RedirectResponse {

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

        return redirect()->route('user.playlists.index');
    }

    public function edit(Playlist $playlist): View|RedirectResponse {

        return view('users.playlists.edit', [
            'playlist' => $playlist,
            'status' => PlaylistStatus::get()
        ]);
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist): RedirectResponse {

        $validated = $request->validated();

        $videos = $request->get('videos', []);

        $playlist->videos()->detach();

        foreach ($videos as $key => $id) {
            $playlist->videos()->attach([
                $id => ['position' => $key]
            ]);
        }

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

    public function favorite (Playlist $playlist) : Response {

        Auth::user()->favorites_playlist()->toggle($playlist->id);
        return response()->noContent();
    }
}
