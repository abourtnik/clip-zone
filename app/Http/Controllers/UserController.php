<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function show (User $user) : View {

        if (Auth::check() && Auth::user()->isSubscribeTo($user)) {

            Subscription::where([
                'user_id' => $user->id,
                'subscriber_id' => Auth::user()->id,
            ])->update([
                'read_at' => now()
            ]);
        }

        return view('users.show', [
            'user' => $user->load([
                'videos' => function ($q) {
                    $q->with('user')
                        ->withCount('views')
                        ->active()
                        ->latest('publication_date');
                },
                'pinned_video' => fn($q) => $q->withCount('views'),
                'reports' => fn($q) => $q->where('user_id', Auth::id())
            ])->loadCount('subscribers', 'videos_views', 'videos')
        ]);

    }

}
