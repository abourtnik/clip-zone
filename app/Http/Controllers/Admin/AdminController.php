<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController
{
    public function index(): View {

        $reports = Report::select('reportable_type', 'reportable_id', DB::raw('MIN(created_at) as first_report_at'), DB::raw('count(*) as reports_count'))
            ->with([
                'reportable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        User::class,
                        Video::class => ['user'],
                        Comment::class => ['user']
                    ]);
                }
            ])
            ->groupBy('reportable_type', 'reportable_id')
            ->orderBy('reports_count', 'desc')
            ->orderBy('first_report_at', 'asc')
            ->limit(10)
            ->get();

        return view('admin.index', [
            'videos_count' => Video::count(),
            'users_count' => User::count(),
            'comments_count' => Comment::count(),
            'reports_count' => Report::count(),
            'reports' => $reports
        ]);
    }

    public function download(string $model): StreamedResponse
    {
        return Storage::download('exports/'.$model.'.csv');
    }
}
