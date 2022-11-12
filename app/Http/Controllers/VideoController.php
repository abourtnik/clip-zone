<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Video;
use Illuminate\Contracts\View\View;

class VideoController
{
    public function index(): View {
        return view('users.index');
    }

    public function comments(Video $video) {

        return CommentResource::collection(
            $video
                ->comments()
                ->whereNull('parent_id')
                ->latest()
                ->paginate(10)
        );
    }
}
