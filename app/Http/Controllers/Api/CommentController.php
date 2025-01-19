<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\RepliesCollection;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    const int REPLIES_PER_PAGE = 10;

    public function __construct()
    {
        $this->authorizeResource('App\Models\Comment,video', ['comment,video']);
    }

    /**
     * @param Video $video
     * @param Request $request
     * @return ResourceCollection
     * @throws AuthorizationException
     */
    public function index(Video $video, Request $request) : ResourceCollection {

        $sort = $request->get('sort', 'top');

        return (CommentResource::collection(
            $video
                ->comments()
                ->public()
                ->whereNull('parent_id')
                ->with([
                    'user',
                    'video' => fn($q) => $q->with(['user', 'pinned_comment']),
                    'replies' => function($q) {
                        return $q
                            ->public()
                            ->with([
                                'user',
                                'video' => fn($q) => $q->with('user'),
                                'replies',
                                'reportByAuthUser',
                            ])
                            ->withCount([
                                'likes',
                                'dislikes'
                            ])
                            ->withExists([
                                'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                                'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                            ])
                            ->orderByRaw('likes_count - dislikes_count DESC')
                            ->latest()
                            ->limit(self::REPLIES_PER_PAGE);
                    },
                    'reportByAuthUser'
                ])
                ->withCount([
                    'replies as total_replies' => fn($q) => $q->public(),
                    'likes',
                    'dislikes'
                ])
                ->withExists([
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'replies as is_video_author_reply' => fn($q) => $q->where('user_id', $video->user->id),
                    'likes as is_video_author_like' => fn($q) => $q->where('user_id', $video->user->id),
                ])
                ->when($video->pinned_comment, fn($query) => $query->orderByRaw('id <> ' .$video->pinned_comment->id))
                ->when($sort === 'top', fn($query) => $query->orderByRaw('likes_count - dislikes_count DESC'))
                ->latest()
                ->simplePaginate(20)
                ->withQueryString()
        ))->additional([
            'count' => $video->comments()->public()->count(),
        ]);
    }

    public function replies (Video $video, Comment $comment, Request $request) : ResourceCollection {

        $sort = $request->get('sort', 'top');

        return (new RepliesCollection(
            $comment
                ->loadCount('replies')
                ->replies()
                ->with([
                    'user',
                    'video' => fn($q) => $q->with('user'),
                    'replies',
                    'reportByAuthUser'
                ])
                ->withCount([
                    'likes',
                    'dislikes'
                ])
                ->withExists([
                    'likes as liked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                    'dislikes as disliked_by_auth_user' => fn($q) => $q->where('user_id', Auth::id())
                ])
                ->when($sort === 'top', fn($query) => $query->orderByRaw('likes_count - dislikes_count DESC'))
                ->latest()
                ->simplePaginate(self::REPLIES_PER_PAGE)
                ->withQueryString()
        ))->additional([
            'total' => $comment->replies_count,
        ]);

    }

    public function store (Video $video, StoreCommentRequest $request) : CommentResource
    {
        $validated = $request->safe()->merge([
            'user_id' => Auth::user()->id,
        ])->toArray();

        $comment = $video->comments()->create($validated);

        return new CommentResource($comment);
    }

    public function update(Video $video, Comment $comment, UpdateCommentRequest $request): CommentResource
    {
        $comment->update($request->validated());

        return new CommentResource($comment);
    }

    public function delete(Video $video, Comment $comment): Response
    {
        $comment->delete();
        return response()->noContent();
    }

    public function pin(Video $video, Comment $comment): Response
    {
        $comment->video->update([
            'pinned_comment_id' => $comment->id
        ]);

        return response()->noContent();
    }

    public function unpin(Video $video, Comment $comment): Response
    {
        $comment->video->update([
            'pinned_comment_id' => null
        ]);

        return response()->noContent();
    }
}
