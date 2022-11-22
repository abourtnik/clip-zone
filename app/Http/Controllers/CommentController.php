<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
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
