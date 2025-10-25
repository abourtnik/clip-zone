<?php

namespace App\Http\Controllers;

use App\Enums\ImageType;
use App\Models\Pivots\Subscription;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function avatar (User $user): Response|BinaryFileResponse
    {
        if ($user->avatar) {
            $path = User::AVATAR_FOLDER.'/'.$user->avatar;
            $file = Storage::get($path);
            return response($file)->header('Content-Type', Storage::mimeType($path));
        }

        return response()->file(public_path(User::DEFAULT_AVATAR), ['Content-Type' => ImageType::PNG->value]);
    }

    public function banner (User $user): Response|BinaryFileResponse
    {
        if ($user->banner) {
            $path = User::BANNER_FOLDER.'/'.$user->banner;
            $file = Storage::get($path);
            return response($file)->header('Content-Type', Storage::mimeType($path));
        }

        return response()->file(public_path(User::DEFAULT_BANNER), ['Content-Type' => ImageType::JPG->value]);
    }

}
