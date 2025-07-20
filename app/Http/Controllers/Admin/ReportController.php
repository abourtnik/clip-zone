<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Enums\VideoStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Interfaces\Reportable;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    public function index() : View {
        return view('admin.reports.index', [
            'reports' => Report::filter()
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
                ->withQueryString()
        ]);
    }

    public function block (string $type, Reportable $reportable) : RedirectResponse {

        $updateInformation = ['banned_at' => now()];

        if ($reportable instanceof User) {
            $updateInformation['status'] = VideoStatus::BANNED;
        }

        $reportable->update($updateInformation);

        Report::query()
            ->where('reportable_type', $reportable->getMorphClass())
            ->where('reportable_id', $reportable->id)
            ->update([
                'status' => ReportStatus::BLOCKED
            ]);

        return redirect()->route('admin.index');

    }

    public function cancel (string $type, Reportable $reportable) : RedirectResponse {

        Report::query()
            ->where('reportable_type', $reportable->getMorphClass())
            ->where('reportable_id', $reportable->id)
            ->update([
                'status' => ReportStatus::CANCELLED
            ]);

        return redirect()->route('admin.index');
    }
}
