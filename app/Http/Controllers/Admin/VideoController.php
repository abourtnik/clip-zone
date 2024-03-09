<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoStatus;
use App\Events\Video\VideoBanned;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VideoController
{
    public function index () : View {
        return view('admin.videos.index', [
            'videos' => Video::filter()
                ->with([
                    'category:id,title',
                    'user' => fn($q) => $q->withCount(['videos', 'subscribers'])
                ])
                ->withCount(['likes', 'dislikes', 'interactions', 'comments', 'views'])
                ->latest('created_at')
                ->paginate(15)
                ->withQueryString()
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
        return redirect()->route('admin.users.index')->withSuccess('Votre export a bien été pris en compte. Vous recevrez une <strong>notification</strong> quand celui-ci sera disponible.');
    }
}
