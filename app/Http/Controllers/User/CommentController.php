<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index() : View {

        return view('users.comments.index', [
            'comments' => Comment::filter()
                ->whereHas('video', function (Builder $query) {
                    $query->where('user_id', Auth::id());
                })
                ->whereNull('parent_id')
                ->with(['video', 'user'])
                ->withCount(['likes', 'dislikes', 'interactions', 'replies'])
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString()
        ]);
    }


    public function destroy(Comment $comment): RedirectResponse {

        $comment->delete();

        return redirect()->back();
    }
}
