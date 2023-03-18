<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function show (User $user) : View {

        $isSubscribed = Auth::user()?->isSubscribeTo($user);

        if ($isSubscribed){
            $this->updateReadDate($user);
        }

        return view('users.show', [
            'user' => $user->load([
                'videos' => function ($q) {
                    $q->with('user')
                        ->withCount('views')
                        ->active()
                        ->latest('publication_date');
                },
                'playlists' => function ($q) {
                    $q->withCount('videos')
                        ->active()
                        ->latest('created_at');
                },
                'pinned_video' => fn($q) => $q->withCount('views'),
                'reports' => fn($q) => $q->where('user_id', Auth::id())
            ])->loadCount('subscribers', 'videos_views', 'videos'),
            'is_subscribed' => $isSubscribed
        ]);
    }

    private function updateReadDate (User $user) : void {
        Subscription::where([
            'user_id' => $user->id,
            'subscriber_id' => Auth::user()->id,
        ])->update([
            'read_at' => now()
        ]);
    }

}
