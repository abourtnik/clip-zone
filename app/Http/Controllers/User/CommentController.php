<?php

namespace App\Http\Controllers\User;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Video::class, 'video');
    }

    public function index() : View {
        return view('users.comments.index', [
            'comments' => Auth::user()->load([
                'videos_comments' => function ($query) {
                    $query->with(['video', 'user'])
                        ->whereNull('parent_id')
                        ->withCount(['likes', 'dislikes', 'interactions', 'replies'])
                        ->orderBy('created_at', 'desc');
                }
            ])->videos_comments->paginate(15)
        ]);
    }


    public function destroy(Comment $comment): RedirectResponse {

        return redirect()->route('user.videos.index');
    }
}
