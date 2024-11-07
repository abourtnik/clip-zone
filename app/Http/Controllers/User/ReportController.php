<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index() : View {
        return view('users.reports.index', [
            'reports' => Report::filter()
                ->where('user_id', Auth::id())
                ->with([
                    'reportable' => function (MorphTo $morphTo) {
                        $morphTo->morphWith([
                            User::class,
                            Video::class => ['user'],
                            Comment::class => ['video', 'user']
                        ]);
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString()
        ]);
    }

    public function cancel (Report $report) : RedirectResponse {

        $report->delete();

        return redirect()->route('user.reports.index');
    }
}
