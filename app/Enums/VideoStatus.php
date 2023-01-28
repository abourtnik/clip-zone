<?php

namespace App\Enums;

enum VideoStatus : int {
    case PUBLIC = 0;
    case PRIVATE = 1;
    case PLANNED = 2;
    case UNLISTED = 3;

    public static function get(): array {
        return array_map(fn($a): array => ['id' => $a->value, 'name' => ucfirst(strtolower(($a->name)))], self::cases());
    }
}
