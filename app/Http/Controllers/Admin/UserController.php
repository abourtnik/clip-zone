<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoStatus;
use App\Events\UserBanned;
use App\Filters\UserFilters;
use App\Jobs\Export;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index (UserFilters $filters) : View {
        return view('admin.users.index', [
            'users' => User::filter($filters)
                ->withCount([
                    'subscribers',
                    'videos',
                    'comments',
                ])->whereNull('is_admin')
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'filters' => $filters->receivedFilters()
        ]);
    }

    public function ban (User $user, Request $request) : RedirectResponse {

        $user->update([
            'banned_at' => now()
        ]);

        if ($request->has('ban_videos')){
            $user->videos()->update([
                'banned_at' => now(),
                'status' => VideoStatus::BANNED
            ]);
        }

        if ($request->has('ban_comments')){
            $user->comments()->update([
                'banned_at' => now(),
            ]);
        }

        UserBanned::dispatch($user);

        return redirect()->route('admin.users.index');
    }

    public function confirm (User $user) : RedirectResponse {

        $user->markEmailAsVerified();

        return redirect()->route('admin.users.index');
    }

    public function export (): RedirectResponse {
        Export::dispatch(User::class, Auth::user());
        return redirect()->route('admin.users.index')->withSuccess('Votre export a bien été pris en compte. Vous recevrez une <strong>notification</strong> quand celui-ci sera disponible.');
    }
}
