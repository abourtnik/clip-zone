<?php

namespace App\Enums;

enum VideoStatus : int {
    case PUBLIC = 1;
    case PRIVATE = 0;

    public static function get(): array {
        return array_map(fn($a): array => ['id' => $a->value, 'name' => ucfirst(strtolower(($a->name)))], self::cases());
    }
}
