<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        if (!Auth::check()) {
            return view('subscription.index');
        }

        return view('subscription.index', [
            'users' => User::active()
                ->whereNotIn('id', Auth::user()->subscriptions()->pluck('users.id')->push(Auth::id())->toArray())
                ->where('show_subscribers', true)
                ->withCount('subscribers')
                ->orderBy('subscribers_count', 'desc')
                ->paginate(18),
            'sorted_videos' => Auth::user()
                ->subscriptions_videos()
                ->with('user')
                ->withCount('views')
                ->latest()
                ->get()
                ->groupBy(fn ($item) => Carbon::parse($item->created_at)->format('Y-m-d'))
                ->all()
        ]);
    }

    public function manage(): View
    {
        return view('subscription.manage');
    }

    public function discover(): View
    {
        return view('subscription.discover', [
            'users' => User::active()
                ->where('show_subscribers', true)
                ->withCount('subscribers')
                ->when(Auth::check(), fn($query) => $query->whereNotIn('id', Auth::user()->subscriptions()->pluck('users.id')->push(Auth::id())->toArray()))
                ->orderBy('subscribers_count', 'desc')
                ->get()
        ]);
    }
}
