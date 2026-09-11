<?php

namespace App\Actions\User;

use App\Models\Pivots\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShowUserAction
{
    private User $user;

    public function data(User $user): array
    {
        $this->user = $user;

        $isSubscribed = $this->isSubscribed();

        if ($isSubscribed){
            $this->updateReadDate();
        }

        return [
            'user' => $this->getUser(),
            'is_subscribed' => $isSubscribed,
        ];
    }

    private function getUser(): User
    {
        return $this->user->load([
            'videos' => function ($q) {
                $q->with('user')
                    ->active()
                    ->latest('published_at')
                    ->limit(8);
            },
            'playlists' => function ($q) {
                $q->withCount('videos')
                    ->withWhereHas('videos', function($q) {
                        $q->active()
                            ->with('user')
                            ->limit(8);
                    })
                    ->active()
                    ->latest('updated_at')
                    ->limit(12);
            },
            'pinned_video',
            'reportByAuthUser'
        ])->loadCount([
            'subscribers',
            'videos_views',
        ]);
    }

    private function isSubscribed(): bool
    {
        return Auth::user()?->isSubscribeTo($this->user) ?? false;
    }

    private function updateReadDate () : void {
        Subscription::where([
            'user_id' => $this->user->id,
            'subscriber_id' => Auth::user()->id,
        ])->update([
            'read_at' => now()
        ]);
    }
}
