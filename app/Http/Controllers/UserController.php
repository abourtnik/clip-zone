<?php

namespace App\Http\Controllers;

use App\Filters\CommentFilters;
use App\Filters\InteractionFilters;
use App\Filters\VideoFilters;
use App\Filters\SubscriberFilters;
use App\Filters\ViewFilters;
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
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserController
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
                        ->orderBy('publication_date', 'desc')
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
            'user' => Auth::user(),
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

    public function update(UpdateUserRequest $request): RedirectResponse {

        $user = auth()->user();

        $validated = $request->safe()->merge([
            'avatar' => $request->file('avatar')?->store('/', 'avatars') ?? $user->avatar,
            'banner' => $request->file('banner')?->store('/', 'banners') ?? $user->banner,
        ])->toArray();

        $user->update($validated);

        return redirect()->route('user.edit');
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

        return redirect()->route('user.edit');
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
