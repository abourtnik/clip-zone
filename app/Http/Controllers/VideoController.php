<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\Comments;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

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
                ->limit(10)
                ->get()
        );
    }
}
