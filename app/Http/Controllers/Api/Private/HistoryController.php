<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Resources\ViewsCollection;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HistoryController
{
    public function index(Request $request): ViewsCollection
    {
        $views = View::query()
            ->joinSub(
                View::query()
                    ->selectRaw('video_id, DATE(view_at) AS view_date, MAX(view_at) AS latest_view_at')
                    ->where('user_id', $request->user()->id)
                    ->groupByRaw('video_id, DATE(view_at)'),
                'daily',
                function (JoinClause $join) {
                    $join->on('views.video_id', '=', 'daily.video_id')
                        ->on(DB::raw('DATE(views.view_at)'), '=', 'daily.view_date')
                        ->on('views.view_at', '=', 'daily.latest_view_at');
                }
            )
            ->where('views.user_id', $request->user()->id)
            ->whereHas('video', fn($q) => $q->active())
            ->with(['video' => fn($q) => $q->with('user')])
            ->orderBy('views.view_at', 'DESC')
            ->get();

        $grouped = $views
            ->groupBy(fn (View $view) => Carbon::parse($view->view_at)->format('Y-m-d'))
            ->map(fn (Collection $views, string $date) => [
                'date'   => $date,
                'views' => $views
            ])
            ->values();


        return new ViewsCollection($grouped);
    }

    public function clear(View $view): Response
    {
        View::query()
            ->where('user_id', $view->user_id)
            ->where('video_id', $view->video_id)
            ->whereDate('view_at', $view->view_at)
            ->delete();

        return response()->noContent();
    }

    public function clearDay(Request $request): Response
    {
        $request->validate([
            'date' => 'required|date:Y-m-d'
        ]);

        View::query()
            ->where('user_id', $request->user()->id)
            ->whereDate('view_at', $request->date)
            ->delete();

        return response()->noContent();
    }

    public function clearAll(Request $request): Response
    {
        $request->user()->views()->delete();

        return response()->noContent();
    }
}
