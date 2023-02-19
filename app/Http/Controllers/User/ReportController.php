<?php

namespace App\Http\Controllers\User;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Filters\ReportFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportRequest;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(ReportFilters $filters) : View {
        return view('users.reports.index', [
            'reports' => Auth::user()->load([
                'user_reports' => function ($query) use ($filters) {
                    $query->filter($filters)
                            ->with([
                                'reportable' => function (MorphTo $morphTo) {
                                    $morphTo->morphWith([
                                        User::class,
                                        Video::class => ['user'],
                                        Comment::class,
                                    ]);
                            }
                        ])
                        ->orderBy('created_at', 'desc');
                }
            ])->user_reports->paginate(15)->withQueryString(),
            'filters' => $filters->receivedFilters(),
            'reasons' => ReportReason::get(),
            'status' => ReportStatus::get(),
        ]);
    }

    public function report (ReportRequest $request) : RedirectResponse {

        $validated = $request->safe()->merge([
            'reportable_type' => $request->get('type'),
            'reportable_id' => $request->get('id'),
        ])->toArray();

        Auth::user()->user_reports()->create($validated);

        return redirect()->back();
    }

    public function cancel () {


    }
}
