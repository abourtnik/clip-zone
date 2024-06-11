<?php

namespace App\Enums\Traits;

trait Stringable
{
    public static function toString(): string {
        return implode(',', array_column(self::cases(), 'value'));
    }

    public static function nameToString(): string {
        return implode(', ', array_column(self::cases(), 'name'));
    }
}
