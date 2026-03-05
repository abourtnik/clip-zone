<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function index() : View {
        return view('admin.comments.index', [
            'comments' => Comment::filter()
                    ->with([
                        'video' => fn(BelongsTo $query) => $query->withTrashed()->with('user'),
                        'user'
                    ])
                    ->whereNull('parent_id')
                    ->withCount(['likes', 'dislikes', 'interactions', 'replies'])
                    ->orderBy('created_at', 'desc')
                    ->withTrashed()
                    ->paginate(12)
                    ->withQueryString()
        ]);
    }

    public function ban (Comment $comment) : RedirectResponse {

        $comment->update([
            'banned_at' => now()
        ]);

        return redirect()->route('admin.comments.index');
    }
}
