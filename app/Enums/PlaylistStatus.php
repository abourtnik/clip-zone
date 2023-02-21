<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum PlaylistStatus : int {

    case PUBLIC = 0;
    case PRIVATE = 1;
    case UNLISTED = 2;

    public static function get () : array {
        return Arr::map(Arr::pluck(self::cases(), 'name', 'value'), function (string $value, int $key) {
            return ucfirst(strtolower($value));
        });
    }

    public function color(): string
    {
        return match($this)
        {
            self::PUBLIC => 'success',
            self::PRIVATE => 'danger',
            self::UNLISTED => 'info',
        };
    }

    public function icon(): string
    {
        return match($this)
        {
            self::PUBLIC => 'earth-europe',
            self::PRIVATE => 'lock',
            self::UNLISTED => 'eye-slash',
        };
    }

    public function name () :string {
        return ucfirst(strtolower($this->name));
    }
}
