<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController
{
    public function home(): View {
        return view('pages.home', [
            'videos' => Video::paginate()
        ]);
    }

    public function trend(): View {
        return view('pages.trend');
    }

    public function video(Video $video): View {
        return view('pages.video', [
            'video' => $video,
            'videos' => Video::all()
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
