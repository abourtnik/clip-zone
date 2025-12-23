<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plan;
use App\Models\Playlist;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageController
{
    public function home(Request $request): View {
        return view('pages.home', [
            'showPremiumModal' => $request->has('premium') && Auth::check()
        ]);
    }

    public function trend(): View
    {
        return view('pages.trend');
    }

    public function history(): View
    {
        return view('pages.history');
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        return view('pages.category', [
            'category' => $category->loadCount([
                'videos' => fn($query) => $query->active()
            ])
        ]);
    }

    public function terms (): View {
        return view('pages.terms');
    }

    public function premium (): View {
        return view('pages.premium', [
            'plans' => Plan::query()->orderBy('duration')->get()
        ]);
    }
}
