<?php

namespace App\Http\Controllers\User;

use App\Events\UserSubscribed;
use App\Filters\CommentFilters;
use App\Filters\InteractionFilters;
use App\Filters\SubscriberFilters;
use App\Filters\VideoFilters;
use App\Filters\ViewFilters;
use App\Helpers\Image;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Intl\Countries;

class ProfileController
{
    public function index(
        VideoFilters $videoFilter,
        CommentFilters $commentFilters,
        SubscriberFilters $subscriberFilters,
        InteractionFilters $interactionFilters,
        ViewFilters $viewFilters,
    ) : View
    {
        return view('users.index', [
            'user' => Auth::user()->load([
                'videos' => function ($query) use ($videoFilter) {
                    $query
                        ->filter($videoFilter)
                        ->withCount(['likes', 'dislikes', 'interactions', 'comments', 'views'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
                },
                'subscribers' => function ($query) use ($subscriberFilters) {
                    $query
                        ->filter($subscriberFilters)
                        ->withCount('subscribers')
                        ->orderBy('subscribe_at', 'desc')
                        ->limit(5);
                },
                "videos_comments" => function ($query) use ($commentFilters) {
                    $query
                        ->filter($commentFilters)
                        ->with(['user', 'video'])
                        ->whereNull('parent_id')
                        ->withCount('replies')
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
                },
                "videos_interactions" => function ($query) use ($interactionFilters) {
                    $query
                        ->filter($interactionFilters)
                        ->with([
                            'likeable' => function (MorphTo $morphTo) use ($interactionFilters) {
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
                'subscribers' => fn($query) => $query->filter($subscriberFilters),
                'videos_views' => fn($query) => $query->filter($viewFilters),
                'videos_interactions' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('interactions', fn($query) => $query->filter($interactionFilters))),
                'videos' => fn($query) => $query->filter($videoFilter),
                'videos_comments' => fn($query) => $query->filter($commentFilters),
                'videos_likes' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('likes', fn($query) => $query->filter($interactionFilters))),
                'videos_dislikes' => fn($query) => $query->whereHasMorph('likeable', Video::class, fn($query) => $query->whereHas('dislikes', fn($query) => $query->filter($interactionFilters))),
            ]),
            'filters' => $videoFilter->receivedFilters()
        ]);
    }

    public function edit(): View {
        return view('users.profile.edit', [
            'user' => Auth::user()->load('premium_subscription'),
            'billing_profile_url' => Auth::user()->stripe_id ? Auth::user()->billingPortalUrl(route('user.edit')) : null,
            'countries' => Countries::getNames()
        ]);
    }

    public function subscribers(SubscriberFilters $filters) : View {
        return view('users.subscribers', [
            'subscribers' => Auth::user()->load([
                'subscribers' => function ($query) use ($filters) {
                    $query
                        ->filter($filters)
                        ->withCount(['subscribers as is_subscribe_to_current_user' => function($query) {
                            $query->where('subscriber_id', Auth::id());
                        }])
                    ->withCount('subscribers')
                    ->orderBy('subscribe_at', 'desc');
                }
            ])->subscribers->paginate(15),
            'filters' => $filters->receivedFilters()
        ]);
    }

    public function update(UpdateUserRequest $request) : RedirectResponse {

        $user = auth()->user();

        $validated = $request->safe()->merge([
            'avatar' => Image::storeAndDelete($request->file('avatar'), $user->avatar, 'avatars'),
            'banner' => Image::storeAndDelete($request->file('banner'), $user->banner, 'banners'),
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

    public function subscribe (User $user) : JsonResponse {
        $subscription = Auth::user()->subscriptions()->toggle($user);
        UserSubscribed::dispatchIf($subscription['attached'], $user, Auth::user());
        return response()->json();
    }

    public function delete(Request $request): RedirectResponse {

        $request->validate([
            'current_password' => 'required',
        ]);

        // Match The Current Password
        if(!Hash::check($request->get('current_password'), Auth::user()->password)){
            return back()->with("error", "Current Password doesn't match !");
        }

        User::find(Auth::id())->delete();
        Auth::logout();
        return redirect()->route('pages.home');
    }

}
