<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportReason;
use App\Filters\ReportFilters;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReportController extends Controller
{
    public function index(ReportFilters $filters) : View {
        return view('admin.reports.index', [
            'reports' => Report::with([
                'user',
                'reportable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        User::class,
                        Video::class => ['user'],
                        Comment::class,
                    ]);
                }
            ])
                ->orderBy('created_at', 'desc')
                ->paginate(12),
            'filters' => $filters->receivedFilters(),
            'reasons' => ReportReason::get()
        ]);
    }
}
