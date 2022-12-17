<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\InteractionsResource;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class VideoController
{
    public function load () : ResourceCollection  {

        return VideoResource::collection(
            Video::active()
                ->with('user')
                ->latest('publication_date')
                ->paginate(24)
        );
    }

    public function show (Video $video) : View {

        return view('videos.show', [
            'video' => $video
                ->load([
                    'user' => fn($q) => $q->withCount('subscribers')
                ])
                ->loadCount([
                    'likes',
                    'dislikes',
                    'comments',
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id())
                ]),
            'videos' => Video::where('id', '!=', $video->id)
                ->with(['user'])
                ->active()
                ->inRandomOrder()
                ->limit(12)
                ->get()
        ]);
    }

    public function comments(Video $video, Request $request) : ResourceCollection {

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
