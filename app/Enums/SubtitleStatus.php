<?php

namespace App\Enums;

use App\Enums\Traits\Listable;

enum SubtitleStatus : int {

    use Listable;

    case PENDING = 0;
    case GENERATED = 1;
    case ERROR = 2;
    case UPLOADED = 3;
}
