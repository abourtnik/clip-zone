<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\InteractionsResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class VideoController
{
    public function index(): View {
        return view('users.index');
    }

    public function comments(Video $video, Request $request) {

        $sort = $request->get('sort', 'top');

        return CommentResource::collection(
            $video
                ->comments()
                ->whereNull('parent_id')
                ->withCount(['likes', 'dislikes'])
                ->when($sort === 'top', fn($query) => $query->orderByRaw('likes_count - dislikes_count DESC'))
                ->when($sort === 'recent', fn($query) => $query->latest())
                ->paginate(10)
        );
    }

    public function interactions(Video $video) {
        return InteractionsResource::collection(
            $video
                ->interactions()
                ->paginate(10)
        );
    }
}
