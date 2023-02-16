<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\View as ViewModel;

class HistoryController extends Controller
{
    public function index(): View
    {
        $views = ViewModel::selectRaw('*')
            ->joinSub(
                ViewModel::selectRaw('video_id, MAX(id) AS id')
                    ->where('user_id' , Auth::id())
                    ->whereHas('video', fn($query) => $query->active())
                    ->groupBy('video_id'), 'b', function ($join) {
                        $join->on('views.video_id', '=', 'b.video_id')->on('views.id', '=', 'b.id');
            })
            ->with(['video' => fn($q) => $q->with('user')])
            ->latest('view_at')
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->view_at)->format('Y-m-d'))
            ->all();

        return view('pages.history', [
            'data' => $views
        ]);
    }

    public function clear()
    {
        Auth::user()->views()->update([
            'user_id' => null
        ]);

        return redirect()->route('history.index');
    }
}
