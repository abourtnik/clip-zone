<?php

namespace App\Http\Controllers\User;

use App\Filters\CommentFilters;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Video::class, 'video');
    }

    public function index(CommentFilters $filters) : View {
        return view('users.comments.index', [
            'comments' => Auth::user()->load([
                'videos_comments' => function ($query) use ($filters) {
                    $query
                        ->filter($filters)
                        ->with([
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
            ])->videos_comments->paginate(15)->withQueryString(),
            'videos' => Auth::user()->videos,
            'users' => User::all(),
            'filters' => $filters->receivedFilters(),
        ]);
    }


    public function destroy(Comment $comment): RedirectResponse {

        $comment->delete();

        return redirect()->route('user.comments.index');
    }
}
