<?php

namespace App\Enums;

use App\Enums\Traits\Stringable;

enum ImageType : string {

    use Stringable;

    case PNG = 'image/png';
    case JPEG = 'image/jpeg';
    case JPG = 'image/jpg';
    case WEBP = 'image/webp';
}
