<?php

namespace App\Http\Controllers\User;

use App\Enums\VideoStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }

    public function index(): View {
        return view('users.videos.index', [
            'videos' => Auth::user()->videos()->paginate(15)
        ]);
    }

    public function create(): View {

        return view('users.videos.create', [
            'status' => VideoStatus::get()
        ]);
    }

    public function store(StoreVideoRequest $request): RedirectResponse {

        // Upload video
        $video = $request->file('file')->store('/', 'videos');

        // Upload Poster
        $poster = $request->file('poster')->store('/', 'thumbnails');

        Auth::user()->videos()->create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'duration' => 0,
            'file' => $video,
            'thumbnail' => $poster,
        ]);

        if ($request->get('action') === 'create') {
            return redirect(route('user.videos.create'));
        }

        return redirect()->route('user.videos.index');
    }

    public function edit(Video $video): View {
        return view('users.videos.edit', [
            'video' => $video,
            'status' => VideoStatus::get()
        ]);
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse {

        if ($request->file('poster')) {
            $poster = $request->file('poster')->store('/', 'posters');
        }

        $video->update($request->validated());

        if ($request->get('action') === 'save') {
            return redirect(route('user.videos.edit', $video));
        }

        return redirect()->route('user.videos.index');
    }

    public function destroy(Video $video): RedirectResponse {

        return redirect()->route('user.videos.index')->with();
    }
}
