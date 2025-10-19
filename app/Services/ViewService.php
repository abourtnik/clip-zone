<?php

namespace App\Services;

use App\Models\Video;
use App\Models\View;
use Illuminate\Support\Facades\Auth;

class ViewService
{
    public const int COOLDOWN_MINUTES = 5;

    private function hasRecentView(Video $video): bool {

        return View::query()
            ->where('video_id', $video->id)
            ->when(Auth::check(), function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->when(Auth::guest(), function ($query) {
                $query->where('ip', request()->getClientIp());
            })
            ->where('view_at', '>=', now()->subMinutes(self::COOLDOWN_MINUTES))
            ->exists();
    }

    private function skipIncrementView(Video $video) : bool
    {
        $manager = app('impersonate');

        return $video->user->is(Auth::user()) || $manager->isImpersonating() || $this->hasRecentView($video);
    }

    public function incrementView(Video $video): void
    {
        if ($this->skipIncrementView($video)) {
            return;
        }

        $video->increment('views');

        $video->viewsHistory()->create([
            'ip' => request()->getClientIp(),
            'user_id' => Auth::user()?->id,
            'user_agent' => request()->userAgent()
        ]);
    }
}
