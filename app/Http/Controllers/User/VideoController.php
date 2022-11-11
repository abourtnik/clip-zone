<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class VideoController
{
    public function index(): View {
        return view('users.videos.index', [
            'videos' => Auth::user()->videos()->paginate(15)
        ]);
    }

    public function create(): View {
        return view('users.videos.create');
    }

    public function store(StoreVideoRequest $request): RedirectResponse {

        // Upload video
        $video = $request->file('file')->store('/', 'videos');

        // Upload Poster
        $poster = $request->file('poster')->store('/', 'posters');

        Auth::user()->videos()->create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'duration' => 0,
            'file' => $video,
            'poster' => $poster,
        ]);

        return redirect()->route('user.videos.index');
    }

    public function edit(Video $video): View {
        return view('users.videos.edit', [
            'video' => $video
        ]);
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse {

        if ($request->file('poster')) {
            $poster = $request->file('poster')->store('/', 'posters');
        }

        $video->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'poster' => $poster ?? null,
        ]);

        return redirect()->route('user.videos.index');
    }

    public function destroy(Video $video): View {
        return view('users.videos', [
            'videos' => Auth::user()->videos()->paginate(15)
        ]);
    }
}
