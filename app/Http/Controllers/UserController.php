<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\CommentResource;
use App\Models\Interfaces\Likeable;
use App\Models\Like;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index(): View {
        return view('users.index');
    }

    public function profile(): View {
        return view('users.profile', [
            'user' => Auth::user()
        ]);
    }

    public function subscribers(): View {
        return view('users.subscribers', [
            'user' => Auth::user()
        ]);
    }

    public function update(UpdateUserRequest $request): RedirectResponse {

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar')->store('/', 'avatars');
        }

        Auth::user()->update([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'avatar' => $avatar ?? null,
        ]);

        return redirect()->route('user.profile');
    }

    public function follow (User $user) : void {

        Auth::user()->subscriptions()->toggle($user);
    }

    public function like (Video $video) : void {
        Auth::user()->like($video);
    }

    public function dislike (Video $video) : void {

        if(Auth::user()->isLike($video)) {
            Auth::user()->likes()->updateExistingPivot($video->id, ['status' => false]);
        }
        else {
            Auth::user()->dislikes()->toggle([$video->id => ['status' => false]]);
        }
    }

    public function comment (Video $video, Request $request) {

        $comment = Auth::user()->comments()->create([
           'video_id' => $video->id,
           'content' => $request->get('content')
        ]);

        return new CommentResource($comment);
    }

}
