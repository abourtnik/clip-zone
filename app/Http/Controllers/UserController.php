<?php

namespace App\Http\Controllers;

use App\Actions\User\ShowUserAction;
use App\Enums\ImageType;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController
{
    public function show (User $user, ShowUserAction $showUserAction) : View {
        return view('users.show', $showUserAction->data($user));
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
