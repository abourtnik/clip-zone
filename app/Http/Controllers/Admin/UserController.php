<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoStatus;
use App\Events\UserBanned;
use App\Exports\UsersExport;
use App\Filters\UserFilters;
use App\Models\User;
use App\Services\ExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController
{
    public function index (UserFilters $filters) : View {
        return view('admin.users.index', [
            'users' => User::filter($filters)
                ->with('premium_subscription')
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

    public function export (ExportService $exportService): RedirectResponse {

        $exportService->generate(UsersExport::class);

        return redirect()->route('admin.users.index')->withSuccess('Your export has been queued. you will receive a notification when it is completed.');
    }
}
