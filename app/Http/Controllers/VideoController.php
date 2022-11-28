<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\InteractionsResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

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
                ->with([
                    'user',
                    'video:id',
                    'replies' => fn($q) => $q->with('user', 'video:id', 'replies')->latest(),
                    'replies.video'
                ])
                ->withCount([
                    'likes',
                    'dislikes',
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id())
                ])
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
