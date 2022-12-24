<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index() : View {
        return view('users.activity.index', [
            'activity_log' => Activity::causedBy(Auth::user())
                ->latest()
                ->get()
                ->groupBy(fn ($item) => Carbon::parse($item->created_at)->format('Y-m-d'))
                ->all()
        ]);
    }
}
