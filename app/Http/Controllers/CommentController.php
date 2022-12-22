<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index(Request $request) : ResourceCollection {

        $sort = $request->get('sort', 'top');
        $target = $request->get('target');

        $video = Video::find($target)->loadCount('comments');

        return (CommentResource::collection(
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
        ))->additional([
            'count' => $video->comments_count,
        ]);
    }

    public function store (Request $request) : CommentResource {

        $comment = Auth::user()->comments()->create([
            'video_id' => $request->get('target'),
            'content' => $request->get('content'),
            'parent_id' => $request->get('parent')
        ]);

        return new CommentResource($comment);
    }

    public function update(Comment $comment, Request $request): CommentResource
    {
        $comment->update([
            'content' => $request->get('content'),
        ]);

        return new CommentResource($comment);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(null, 204);
    }
}
