<?php

namespace App\Http\Controllers;

use App\Actions\User\ShowUserAction;
use App\Enums\ImageType;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController
{
    public function show (User $user, ShowUserAction $showUserAction) : View {
        return view('users.show', $showUserAction->data($user));
    }

    public function avatar (User $user): RedirectResponse|BinaryFileResponse
    {
        if ($user->avatar) {
            $url = Storage::temporaryUrl(
                User::AVATAR_FOLDER.'/'.$user->avatar,
                now()->addMinutes(5),
                ['ResponseCacheControl' => 'private, max-age=300'],
            );

            return redirect($url)->header('Cache-Control', 'private, max-age=240');
        }

        return response()->file(public_path(User::DEFAULT_AVATAR), [
            'Content-Type' => ImageType::PNG->value,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function banner (User $user): RedirectResponse|BinaryFileResponse
    {
        if ($user->banner) {
            $url = Storage::temporaryUrl(
                User::BANNER_FOLDER.'/'.$user->banner,
                now()->addMinutes(5),
                ['ResponseCacheControl' => 'private, max-age=300'],
            );
            return redirect($url)->header('Cache-Control', 'private, max-age=240');
        }

        return response()->file(public_path(User::DEFAULT_BANNER), [
            'Content-Type' => ImageType::JPG->value,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
