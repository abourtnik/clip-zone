<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UploadComposer
{
    /**
     * Bind data to the view.
     *
     * @param View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'available_uploads' => $this->getAvailableUploads(),
            'available_space' =>$this->getAvailableSpace(),
        ]);
    }

    private function getAvailableUploads(): int
    {
        return max(0, config('plans.free.max_uploads') - Auth::user()->uploaded_videos);
    }

    private function getAvailableSpace(): int
    {
        return max(0, config('plans.'.Auth::user()->plan.'.max_videos_storage') - Auth::user()->uploaded_videos_size);
    }
}
