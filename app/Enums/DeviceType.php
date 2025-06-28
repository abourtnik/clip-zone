<?php

namespace App\Enums;

use App\Enums\Traits\Stringable;

enum DeviceType : string {

    use Stringable;

    case UNKNOWN = 'UNKNOWN';
    case PHONE = 'PHONE';
    case TABLET = 'TABLET';
    case TV = 'TV';
    case DESKTOP = 'DESKTOP';

    public function icon(): string
    {
        return match($this)
        {
            self::UNKNOWN => 'question',
            self::PHONE => 'mobile',
            self::TABLET => 'tablet',
            self::TV => 'tv',
            self::DESKTOP => 'desktop',
        };
    }
}
