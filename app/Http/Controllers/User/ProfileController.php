<?php

namespace App\Http\Controllers\User;

use App\Helpers\File;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Pivots\Subscription;
use App\Models\User;
use App\Models\Video;
use App\Notifications\Account\DeleteAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Intl\Countries;

class ProfileController
{
    public function index() : View
    {
        return view('users.index', [
            'user' => Auth::user()->load([
                'videos' => function ($query) {
                    $query
                        ->filter()
                        ->withCount(['likes', 'dislikes', 'interactions', 'comments', 'views'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
                },
                'subscribers' => function ($query) {
                    $query
                        ->filter()
                        ->withCount('subscribers')
                        ->orderBy('subscribe_at', 'desc')
                        ->limit(5);
                },
                "videos_comments" => function ($query) {
                    $query
                        ->filter()
                        ->with(['user', 'video'])
                        ->whereNull('parent_id')
                        ->withCount('replies')
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
                },
                "videos_interactions" => function ($query) {
                    $query
                        ->filter()
                        ->with([
                            'likeable' => function (MorphTo $morphTo) {
                                $morphTo->morphWith([
                                    Video::class => ['user']
                                ]);
                            },
                            'user'
                        ])
                        ->orderBy('perform_at', 'desc')
                        ->limit(5);
                }
            ])->loadCount([
                'subscribers' => fn($query) => $query->filter(),
                'videos_views' => fn($query) => $query->filter(),
                'videos_interactions' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('interactions', fn($query) => $query->filter())),
                'videos' => fn($query) => $query->filter(),
                'videos_comments' => fn($query) => $query->filter(),
                'videos_likes' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('likes', fn($query) => $query->filter())),
                'videos_dislikes' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('dislikes', fn($query) => $query->filter())),
            ])
        ]);
    }

    public function edit(): View {
        return view('users.profile.edit', [
            'user' => Auth::user()->load('premium_subscription'),
            'billing_profile_url' => Auth::user()->stripe_id ? Auth::user()->billingPortalUrl(route('user.edit')) : null,
            'countries' => Countries::getNames()
        ]);
    }

    public function subscribers() : View {
        return view('users.subscribers', [
            'subscriptions' => Subscription::query()
                ->where('user_id', Auth::id())
                ->filter()
                ->with([
                    'subscriber' => function ($query) {
                        return $query
                            ->withCount('subscribers')
                            ->withExists([
                                'subscribers as is_current_user_subscribe' => fn($query) => $query->where('subscriber_id', Auth::id())
                            ]);
                    }
                ])
                ->orderBy('subscribe_at', 'desc')
                ->paginate(15)
                ->withQueryString()
        ]);
    }

    public function update(UpdateUserRequest $request) : RedirectResponse {

        $user = auth()->user();

        $validated = $request->safe()->merge([
            'avatar' => File::storeAndDelete($request->file('avatar'), User::AVATAR_FOLDER, $user->avatar),
            'banner' => File::storeAndDelete($request->file('banner'), User::BANNER_FOLDER, $user->banner),
        ])->toArray();

        $user->update($validated);

        return redirect()->route('user.edit')->with(['status' => 'Your profile information has been updated successfully !']);
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

        $user->updatePassword($request->get('new_password'));

        return redirect()->route('user.edit')->with(['status' => 'Your password has been updated successfully !']);
    }

    public function delete(Request $request): RedirectResponse {

        $request->validate([
            'current_password' => 'required',
        ]);

        // Match The Current Password
        if(!Hash::check($request->get('current_password'), Auth::user()->password)){
            return back()->with("error", "Current Password doesn't match !");
        }

        Auth::user()->notify(new DeleteAccount());

        User::find(Auth::id())->delete();
        Auth::logout();

        return redirect()->route('pages.home');
    }

}
