<?php

namespace App\Enums;

use App\Enums\Traits\Stringable;

enum VideoType : string {

    use Stringable;

    case MP4 = 'video/mp4';
    case OGG = 'video/ogg';
    case WEBM = 'video/webm';
    case MOV = 'video/quicktime';
    case AVI = 'video/x-msvideo';
    case WMV = 'video/x-ms-asf';
    case SWF = 'application/x-shockwave-flash';
    case MPEG = 'video/mpeg';
    case FLV = 'video/x-flv';
    case MKV = 'video/x-matroska';
}
