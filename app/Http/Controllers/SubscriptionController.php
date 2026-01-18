<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        if (!Auth::check()) {
            return view('subscription.index');
        }

        $subscription_count = Auth::user()->subscriptions->count();

        if ($subscription_count) {
            return view('subscription.index', [
                'subscription_count' => $subscription_count,
            ]);
        } else {
            return view('subscription.index', [
                'subscription_count' => $subscription_count,
                'users' => User::active()
                    ->whereNot('id', Auth::id())
                    ->where('show_subscribers', true)
                    ->withCount([
                        'subscribers',
                        'videos' => fn($query) => $query->active(),
                    ])
                    ->having('videos_count', '>', 0)
                    ->orderBy('subscribers_count', 'desc')
                    ->orderBy('videos_count', 'desc')
                    ->orderBy('created_at', 'asc')
                    ->limit(100)
                    ->get(),
            ]);
        }
    }

    public function manage(): View
    {
        return view('subscription.manage', [
            'subscriptions' => Auth::user()
                ->subscriptions()
                ->withCount([
                    'subscribers',
                    'videos'
                ])
                ->latest('subscribe_at')
                ->get()
        ]);
    }

    public function discover(): View
    {
        return view('subscription.discover', [
            'users' => User::active()
                ->where('show_subscribers', true)
                ->withCount([
                    'subscribers',
                    'videos' => fn($query) => $query->active(),
                ])
                ->having('videos_count', '>', 0)
                ->when(Auth::check(), fn($query) => $query->whereNotIn('id', Auth::user()->subscriptions()->pluck('users.id')->push(Auth::id())->toArray()))
                ->orderBy('subscribers_count', 'desc')
                ->orderBy('videos_count', 'desc')
                ->orderBy('created_at', 'asc')
                ->limit(100)
                ->get()
        ]);
    }
}
