<?php

namespace App\Http\Controllers\User;

use App\Enums\SubtitleStatus;
use App\Helpers\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subtitle\StoreSubtitleRequest;
use App\Http\Requests\Subtitle\UpdateSubtitleRequest;
use App\Models\Subtitle;
use App\Models\Video;
use App\Services\SubtitleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class SubtitleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Subtitle::class, 'subtitle');
    }

    public function list(): View {
        return view('users.subtitles.list', [
            'videos' => Video::query()
                ->public()
                ->where('user_id', Auth::id())
                ->withCount('subtitles')
                ->with([
                    'subtitles' => fn($q) => $q->latest('updated_at')->limit(1)
                ])
                ->latest()
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function index(Video $video): View {
        return view('users.subtitles.index', [
            'video' => $video,
            'subtitles' => Subtitle::query()
                ->where('video_id', $video->id)
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString()
        ]);
    }

    public function create(Video $video, SubtitleService $subtitleService): View {
        return view('users.subtitles.create', [
            'video' => $video,
            'languages' => $subtitleService->getRemainingLanguages($video),
            'status' => SubtitleStatus::get(),
        ]);
    }

    public function store (StoreSubtitleRequest $request, Video $video) : RedirectResponse {
        $validated = $request->safe()->merge([
            'file' => File::storeAndDelete($request->file('file'), Subtitle::FILE_FOLDER),
        ])->toArray();

        $video->subtitles()->create($validated);

        return redirect()->route('user.videos.subtitles.index', $video);
    }

    public function edit(Subtitle $subtitle): View
    {
        return view('users.subtitles.edit', [
            'subtitle' => $subtitle,
            'video' => $subtitle->video,
            'status' => SubtitleStatus::get(),
        ]);
    }

    public function update(UpdateSubtitleRequest $request, Subtitle $subtitle): RedirectResponse {

        $validated = $request->safe()->merge([
            'file' => File::storeAndDelete($request->file('file'), Subtitle::FILE_FOLDER, $subtitle->file),
        ])->toArray();

        $subtitle->update($validated);

        if ($request->get('action') === 'save') {
            return redirect(route('user.subtitles.edit', $subtitle));
        }

        return redirect()->route('user.videos.subtitles.index', $subtitle->video);
    }

    public function destroy(Subtitle $subtitle): RedirectResponse {

        $subtitle->delete();

        File::deleteIfExist($subtitle->file, Subtitle::FILE_FOLDER);

        return redirect()->back();
    }
}
