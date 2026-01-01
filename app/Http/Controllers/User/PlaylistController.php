<?php

namespace App\Http\Controllers\User;

use App\Actions\Playlist\StorePlaylistAction;
use App\Actions\Playlist\UpdatePlaylistAction;
use App\Enums\PlaylistSort;
use App\Enums\PlaylistStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Playlist\StorePlaylistRequest;
use App\Http\Requests\Playlist\UpdatePlaylistRequest;
use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
                ->where('is_deletable', true)
                ->withCount(['videos'])
                ->latest('created_at')
                ->paginate(15)
                ->withQueryString()
        ]);
    }

    public function create(): View {
        return view('users.playlists.create', [
            'status' => PlaylistStatus::get(),
            'sorts' => PlaylistSort::get()
        ]);
    }

    public function store(StorePlaylistRequest $request, StorePlaylistAction $storePlaylistAction): RedirectResponse {

        $storePlaylistAction->execute($request);

        return redirect()->route('user.playlists.index');
    }

    public function edit(Playlist $playlist): View|RedirectResponse {

        return view('users.playlists.edit', [
            'playlist' => $playlist,
            'status' => PlaylistStatus::get(),
            'sorts' => PlaylistSort::get()
        ]);
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist, UpdatePlaylistAction $updatePlaylistAction): RedirectResponse {

        $updatePlaylistAction->execute($request, $playlist);

        if ($request->string('action')->is('save')) {
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
