<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HeaderComposer
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
            'unread_notifications' => Auth::user()?->unreadNotifications->count(),
            'show_sidebar' => !in_array(request()->route()?->getName(), ['video.show', 'pages.premium']),
        ]);
    }
}
