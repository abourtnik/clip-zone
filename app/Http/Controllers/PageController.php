<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController
{
    public function home(): View {
        return view('pages.home');
    }

    public function trend(): View {
        return view('pages.trend');
    }

    public function liked(): View {
        return view('pages.liked');
    }

    public function history(): View {
        return view('pages.history');
    }

    public function discover(): View {
        return view('pages.discover', [
           'users' => User::active()
               ->where('show_subscribers', true)
               ->withCount('subscribers')
               ->when(Auth::check(), fn($query) => $query->whereNotIn('id', Auth::user()->subscriptions()->pluck('users.id')->push(Auth::id())->toArray()))
               ->orderBy('subscribers_count', 'desc')
               ->get()
        ]);
    }

    public function category(string $slug): View {

        $category = Category::where('slug', $slug)->firstOrFail();

        return view('pages.category', [
            'category' => $category->loadCount([
                'videos' => fn($query) => $query->active()
            ])
        ]);
    }

    public function subscriptions(): View {

        if (!Auth::check()) {
            return view('pages.subscriptions');
        }

        return view('pages.subscriptions', [
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

    public function user(User $user): View {
        return view('pages.user', [
            'user' => $user->load([
                'videos' => function ($q) {
                    $q->with('user')
                        ->withCount('views')
                        ->active()
                        ->latest('publication_date');
                }
            ])->loadCount('subscribers', 'videos_views')
        ]);
    }
}
