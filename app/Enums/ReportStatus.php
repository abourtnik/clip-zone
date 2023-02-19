<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum ReportStatus : int {

    case PROGRESS = 0;
    case ACCEPTED = 1;
    case REFUSED = 2;

    public static function get () : array {
        return Arr::map(Arr::pluck(self::cases(), 'name', 'value'), function (string $value, int $key) {
            return ucfirst(strtolower($value));
        });
    }
}
