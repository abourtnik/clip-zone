<?php

namespace App\Enums;

use App\Enums\Traits\Listable;
use App\Enums\Traits\Stringable;

enum CustomPlaylistType : string {

    use Listable, Stringable;
    case WATCH_LATER = 'watch-later';
    case LIKED_VIDEOS = 'liked-videos';

}
