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
            'videos' => Video::active()->latest('publication_date')->paginate(12)
        ]);
    }

    public function user(User $user): View {
        return view('pages.user', [
            'user' => $user
        ]);
    }

    public function search(Request $request): View {
        return view('pages.search', [
            'search' => $request->get('search')
        ]);
    }
}
