<?php

namespace App\Http\Controllers\User;

use App\Filters\CommentFilters;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(CommentFilters $filters) : View {
        return view('users.comments.index', [
            'comments' => Comment::query()
                ->where('user_id', Auth::id())
                ->whereNull('parent_id')
                ->filter($filters)
                ->with([
                    'video',
                    'user' => function ($query) {
                        $query->withCount(['subscriptions as is_subscribe_to_current_user' => function ($query) {
                            $query->where('user_id', Auth::id());
                        }]);
                    }
                ])
                ->withCount(['likes', 'dislikes', 'interactions', 'replies'])
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString(),
            'videos' => Auth::user()->videos,
            'filters' => $filters->receivedFilters(),
        ]);
    }


    public function destroy(Comment $comment): RedirectResponse {

        $comment->delete();

        return redirect()->back();
    }
}
