<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum VideoStatus : int {

    case PUBLIC = 0;
    case PRIVATE = 1;
    case PLANNED = 2;
    case UNLISTED = 3;
    case DRAFT = 4;
    case BANNED = 5;
    case FAILED = 6;

    public static function getAll(): array {
        return self::get(self::cases());
    }

    public static function getActive(): array {
        return [
            self::PUBLIC->value => ucfirst(strtolower(self::PUBLIC->name)),
            self::PRIVATE->value => ucfirst(strtolower(self::PRIVATE->name)),
            self::PLANNED->value => ucfirst(strtolower(self::PLANNED->name)),
            self::UNLISTED->value => ucfirst(strtolower(self::UNLISTED->name)),
        ];
    }

    public static function validStatus () : array {

        return [
            self::PUBLIC->value,
            self::PRIVATE->value,
            self::PLANNED->value,
            self::UNLISTED->value,
        ];
    }

    private static function get (array $data) : array {
        return Arr::map(Arr::pluck($data, 'name', 'value'), function (string $value, int $key) {
            return ucfirst(strtolower($value));
        });
    }
}
