<?php

namespace App\Http\Controllers\User;

use App\Actions\Video\StoreVideoAction;
use App\Actions\Video\UpdateVideoAction;
use App\Charts\VideoStatsChart;
use App\Enums\VideoStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }

    public function index(): View {
        return view('users.videos.index', [
            'videos' => Video::filter()
                ->where('user_id', Auth::id())
                ->with([
                    'category:id,title',
                    'user:id,pinned_video_id',
                ])
                ->withCount(['likes', 'dislikes', 'interactions', 'comments'])
                ->latest('updated_at')
                ->latest('id')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function create(Video $video): View|RedirectResponse {

        // Refresh video data to get real uploaded_at
        $video->refresh();

        if (!$video->is_draft) {
            return redirect()->route('user.videos.edit', $video);
        }

        return view('users.videos.create', [
            'video' => $video,
            'status' => VideoStatus::getActive(),
            'categories' => Category::all(),
            'languages' => Video::languages(),
            'playlists' => Auth::user()->playlists()->where('is_deletable', true)->get()
        ]);
    }

    public function store(StoreVideoRequest $request, Video $video, StoreVideoAction $storeVideoAction): RedirectResponse {

        $storeVideoAction->execute($request, $video);

        return redirect()->route('user.videos.index');
    }

    public function show(Video $video): View {
        return view('users.videos.show', [
            'video' => $video->loadCount([
                'likes',
                'dislikes',
                'comments',
                'interactions'
            ]),
            'chart' => (new VideoStatsChart($video))->build()
        ]);
    }

    public function edit(Video $video): View|RedirectResponse {

        if ($video->is_draft) {
            return redirect()->route('user.videos.create', $video);
        }

        return view('users.videos.edit', [
            'video' => $video,
            'status' => VideoStatus::getActive(),
            'categories' => Category::all(),
            'languages' => Video::languages(),
            'playlists' => Playlist::query()
                ->where('user_id', Auth::id())
                ->where('is_deletable', true)
                ->withExists([
                    'videos as has_video' => fn($q) => $q->where('video_id', $video->id)
                ])
                ->get()
        ]);
    }

    public function update(UpdateVideoRequest $request, Video $video, UpdateVideoAction $updateVideoAction): RedirectResponse {

        $updateVideoAction->execute($request, $video);

        if ($request->string('action')->is('save')) {
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

    public function massDelete(Request $request)
    {
        $ids = $request->get('ids');

        $videos = Auth::user()->videos()->whereIn('id', $ids);

        $count = $videos->count();

        $videos->each(fn(Video $video) => $video->delete());

        return redirect()
            ->route('user.videos.index')
            ->withSuccess(trans_choice('videos', $count). ' deleted with success');
    }
}
