<?php

namespace App\Http\Controllers\User;

use App\Actions\User\ShowDashboardAction;
use App\Http\Controllers\Controller;
use App\Models\Pivots\Subscription;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index(ShowDashboardAction $showDashboardAction) : View
    {
        return view('users.index', $showDashboardAction->data());
    }

    public function subscribers() : View {
        return view('users.subscribers', [
            'subscriptions' => Subscription::query()
                ->where('user_id', Auth::id())
                ->filter()
                ->with([
                    'subscriber' => function ($query) {
                        return $query
                            ->withCount('subscribers')
                            ->withExists([
                                'subscribers as is_current_user_subscribe' => fn($query) => $query->where('subscriber_id', Auth::id())
                            ]);
                    }
                ])
                ->orderBy('subscribe_at', 'desc')
                ->paginate(15)
                ->withQueryString()
        ]);
    }
}
