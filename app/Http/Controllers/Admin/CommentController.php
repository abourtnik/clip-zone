<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CommentFilters;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function index(CommentFilters $filters) : View {
        return view('admin.comments.index', [
            'comments' => Comment::filter($filters)
                    ->with([
                        'video' => fn($q) => $q->with('user'),
                        'user'
                    ])
                    ->whereNull('parent_id')
                    ->withCount(['likes', 'dislikes', 'interactions', 'replies'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(12)
                    ->withQueryString(),
            'filters' => $filters->receivedFilters()
        ]);
    }

    public function ban (Comment $comment) : RedirectResponse {

        $comment->update([
            'banned_at' => now()
        ]);

        return redirect()->route('admin.comments.index');
    }
}
