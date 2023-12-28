<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoStatus;
use App\Events\Video\VideoBanned;
use App\Filters\VideoFilters;
use App\Jobs\Export;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VideoController
{
    public function index (VideoFilters $filters) : View {
        return view('admin.videos.index', [
            'videos' => Video::filter($filters)->with([
                'category:id,title',
                'user' => fn($q) => $q->withCount(['videos', 'subscribers'])
            ])
            ->withCount(['likes', 'dislikes', 'interactions', 'comments', 'views'])
            ->latest('created_at')
            ->paginate(15),
            'filters' => $filters->receivedFilters(),
            'status' => VideoStatus::getAll(),
            'categories' => Category::all(),
            'users' => User::all(),
        ]);
    }

    public function ban (Video $video) : RedirectResponse {

        $video->update([
           'status' => VideoStatus::BANNED,
           'banned_at' => now()
        ]);

        VideoBanned::dispatch($video);

        return redirect()->route('admin.videos.index');
    }

    public function export (): RedirectResponse {
        Export::dispatch(User::class, Auth::user());
        return redirect()->route('admin.users.index')->withSuccess('Votre export a bien été pris en compte. Vous recevrez une <strong>notification</strong> quand celui-ci sera disponible.');
    }
}
