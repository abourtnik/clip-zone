<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * @param Request $request
     * @return ResourceCollection
     * @throws AuthorizationException
     */
    public function list(Request $request) : ResourceCollection {

        $sort = $request->get('sort', 'top');
        $video_id = $request->get('video_id');

        $video = Video::findOrFail($video_id)->loadCount('comments');

        $this->authorize('list', [Comment::class, $video]);

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
                ->when($sort === 'newest', fn($query) => $query->latest())
                ->paginate(10)
        ))->additional([
            'count' => $video->comments_count,
        ]);
    }

    public function replies (Comment $comment, Request $request) : ResourceCollection {

        $sort = $request->get('sort', 'top');

        return (CommentResource::collection(
            $comment
                ->loadCount('replies')
                ->replies()
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
        ));

    }

    public function store (StoreCommentRequest $request) : CommentResource {

        $comment = Auth::user()->comments()->create($request->validated());

        return new CommentResource($comment);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): CommentResource
    {
        $comment->update($request->validated());

        return new CommentResource($comment);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        Activity::forSubject($comment)->delete();

        return response()->json(null, 204);
    }
}
