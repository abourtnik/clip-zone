<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum Languages : string {

    case Arabic = 'ar';
    case Chinese = 'zh';
    case Deutch = 'nl';
    case English = 'en';
    case French = 'fr';
    case German = 'de';
    case Hindi = 'hi';
    case Italian = 'it';
    case Korean = 'ko';
    case Spanish = 'es';
    case Portuguese = 'pt';
    case Russian = 'ru';

    public static function get(): array {
        return Arr::pluck(self::cases(), 'name', 'value');
    }
}
