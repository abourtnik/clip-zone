<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\CommentResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Intl\Countries;

class UserController
{
    public function index(): View {
        return view('users.index');
    }

    public function profile(): View {
        return view('users.profile.index', [
            'user' => Auth::user(),
            'countries' => Countries::getNames()
        ]);
    }

    public function subscribers(): View {
        return view('users.subscribers', [
            'user' => Auth::user()
        ]);
    }

    public function comments(): View {
        return view('users.comments', [
            'comments' => Auth::user()->videos_comments()->paginate(15)
        ]);
    }

    public function update(UpdateUserRequest $request): RedirectResponse {

        $user = auth()->user();

        $validated = $request->safe()->merge([
            'avatar' => $request->file('avatar')?->store('/', 'avatars') ?? $user->avatar,
            'banner' => $request->file('banner')?->store('/', 'banners') ?? $user->banner,
        ])->toArray();

        $user->update($validated);

        return redirect()->route('user.profile');
    }

    public function updatePassword(Request $request): RedirectResponse {

        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(6)]
        ]);

        // Match The Current Password
        if(!Hash::check($request->get('current_password'), $user->password)){
            return back()->with("error", "Current Password doesn't match !");
        }

        // Update new password

        $user->update([
            'password' => $request->get('new_password')
        ]);

        return redirect()->route('user.profile');
    }

    public function follow (User $user) : JsonResponse {
        if (Auth::user()->isNot($user)) {
            Auth::user()->subscriptions()->toggle($user);
            return response()->json([
               'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You can\'t subscribe yourself'
            ]);
        }
    }

    public function comment (Video $video, Request $request) {

        $comment = Auth::user()->comments()->create([
           'video_id' => $video->id,
           'content' => $request->get('content')
        ]);

        return new CommentResource($comment);
    }

    public function delete(User $user): RedirectResponse {

        foreach ($user->videos as $video){
            $video->comments()->delete();
            $video->interactions()->delete();
        }

        $user->subscribers()->delete();
        $user->subscriptions()->delete();
        $user->comments()->delete();
        $user->videos_interactions()->delete();

        $user->delete();

        return redirect()->route('pages.home');
    }

}
