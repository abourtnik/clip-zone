<?php

namespace App\Enums;

enum ThumbnailStatus : int {
    case PENDING = 0;
    case GENERATED = 1;
    case ERROR = 2;
    case UPLOADED = 3;
}
