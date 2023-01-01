<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
                    $query->with([
                        'video',
                        'user' => function ($query) {
                            $query->withCount(['subscriptions as is_subscribe_to_current_user' => function ($query) {
                                $query->where('user_id', Auth::id());
                            }]);
                        }
                    ])
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
