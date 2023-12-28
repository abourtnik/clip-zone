<?php

namespace App\Http\Controllers;

use App\Models\Pivots\Subscription;
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
                        ->latest('publication_date')
                        ->limit(8);
                },
                'playlists' => function ($q) {
                    $q->withCount('videos')
                      ->withWhereHas('videos', function($q) {
                            $q->active()
                                ->withCount('views')
                                ->with('user')
                                ->limit(8);
                            })
                        ->active()
                        ->latest('updated_at')
                        ->limit(6);
                },
                'pinned_video' => fn($q) => $q->withCount('views'),
                'reportByAuthUser'
            ])->loadCount([
                'subscribers',
                'videos_views',
                'videos' => fn($q) => $q->active(),
                'playlists' => fn($q) => $q->active()
            ]),
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
