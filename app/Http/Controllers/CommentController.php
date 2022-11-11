<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(Request $request): CommentResource
    {
        $comment = Auth::user()->comments()->create([
            'video_id' => $request->get('target'),
            'content' => $request->get('content'),
            'parent_id' => $request->get('parent')
        ]);

        return new CommentResource($comment);
    }
}
