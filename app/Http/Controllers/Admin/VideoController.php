<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\Export;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VideoController
{
    public function index () : View {
        return view('admin.videos.index', [
            'videos' => Video::query()->latest()->paginate(15)
        ]);
    }

    public function export (): RedirectResponse {
        Export::dispatch(User::class, Auth::user());
        return redirect()->route('admin.users.index')->withSuccess('Votre export a bien été pris en compte. Vous recevrez une <strong>notification</strong> quand celui-ci sera disponible.');
    }
}
