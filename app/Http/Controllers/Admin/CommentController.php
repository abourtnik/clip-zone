<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\View\View;


class CommentController extends Controller
{
    public function index() : View {
        return view('admin.comments.index', [
            'comments' => Comment::with(['video', 'user'])
                    ->whereNull('parent_id')
                    ->withCount(['likes', 'dislikes', 'interactions', 'replies'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(12),
            'filters' => []
        ]);
    }
}
