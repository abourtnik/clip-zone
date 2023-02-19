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

    public static function getAll(): array {
        return self::get(self::cases());
    }

    public static function getActive(): array {
        return self::get(Arr::where(self::cases(), fn ($value, $key) => $value->name !== self::DRAFT->name));
    }

    private static function get (array $data) : array {
        return Arr::map(Arr::pluck($data, 'name', 'value'), function (string $value, int $key) {
            return ucfirst(strtolower($value));
        });
    }

    public static function validStatus () : array {

        return [
            self::PUBLIC->value,
            self::PRIVATE->value,
            self::PLANNED->value,
            self::UNLISTED->value,
        ];
    }
}
