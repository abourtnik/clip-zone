<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoStatus;
use App\Events\UserBanned;
use App\Exports\UsersExport;
use App\Models\User;
use App\Notifications\Account\DeleteAccount;
use App\Services\ExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController
{
    public function index () : View {
        return view('admin.users.index', [
            'users' => User::filter()
                ->with('premium_subscription')
                ->withCount([
                    'subscribers',
                    'videos',
                    'comments',
                    'interactions'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString()
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

        return redirect(url()->previous());
    }

    public function confirm (User $user) : RedirectResponse {

        $user->markEmailAsVerified();

        return redirect(url()->previous());
    }

    public function delete (User $user) : RedirectResponse {

        $user->notify(new DeleteAccount());

        $user->delete();

        return redirect(url()->previous());
    }

    public function export (Request $request, ExportService $exportService): RedirectResponse {

        $exportService->generate(UsersExport::class, $request->query());

        return redirect()
            ->route('admin.users.index', $request->query())
            ->withSuccess('Your export has been queued. you will receive a notification when it is completed.');
    }
}
