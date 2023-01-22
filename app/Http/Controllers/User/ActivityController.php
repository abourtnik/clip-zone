<?php

namespace App\Http\Controllers\User;

use App\Filters\ActivityFilters;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Interaction;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index(ActivityFilters $filters) : View {
        return view('users.activity.index', [
            'activity_log' => Activity::causedBy(Auth::user())
                ->filter($filters)
                ->with([
                    'subject' => function (MorphTo $morphTo) {
                        $morphTo->morphWith([
                            Comment::class => ['video'],
                            Interaction::class => [
                                'likeable' => function (MorphTo $morphTo) {
                                    $morphTo->morphWith([
                                        Video::class,
                                    ]);
                                }
                            ],
                        ]);
                    }
                ])
                ->latest()
                ->get()
                ->groupBy(fn ($item) => Carbon::parse($item->created_at)->format('Y-m-d'))
                ->all(),
            'filters' => $filters->receivedFilters(),
        ]);
    }
}
