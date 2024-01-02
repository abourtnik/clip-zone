<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Filters\ReportFilters;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    public function index(ReportFilters $filters) : View {
        return view('admin.reports.index', [
            'reports' => Report::filter($filters)
                ->with([
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
                ->paginate(12)
                ->withQueryString(),
            'filters' => $filters->receivedFilters(),
            'reasons' => ReportReason::get(),
            'status' => ReportStatus::get(),
            'users' => User::all(),
        ]);
    }

    public function accept (Report $report) : RedirectResponse {

        $report->update([
           'status' => ReportStatus::ACCEPTED
        ]);

        return redirect()->route('admin.reports.index');

    }

    public function reject (Report $report) : RedirectResponse {

        $report->update([
            'status' => ReportStatus::REJECTED
        ]);

        return redirect()->route('admin.reports.index');
    }
}
