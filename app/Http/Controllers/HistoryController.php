<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(): View
    {
        $views = Auth::user()
            ->views()
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
        return redirect()->route('history.index');
    }
}
