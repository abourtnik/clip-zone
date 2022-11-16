<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PageController
{
    public function home(): View {
        return view('pages.home', [
            'videos' => Video::active()->latest('publication_date')->paginate(12)
        ]);
    }

    public function trend(): View {
        return view('pages.trend', [
            'videos' => Video::active()->latest('publication_date')->paginate(12)
        ]);
    }

    public function video(Video $video): View {

        //$video->increment('views');

        return view('pages.video', [
            'video' => $video,
            'videos' => Video::where('id', '!=', $video->id)
                ->active()
                ->inRandomOrder()
                ->limit(12)
                ->get()
        ]);
    }

    public function user(User $user): View {
        return view('pages.user', [
            'user' => $user
        ]);
    }

    public function search(Request $request): View {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        $videos = Video::active()
            ->where(fn($query) =>
                $query->where('title', 'LIKE', $match)
                    ->orWhere('description', 'LIKE', $match)
                    ->OrWhereHas('user', fn($query) => $query->where('username', 'LIKE', $match))
            )
            ->latest('publication_date')
            ->paginate(12);

        return view('pages.search', [
            'search' => $q,
            'videos' => $videos
        ]);
    }
}
