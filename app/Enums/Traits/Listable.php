<?php

namespace App\Enums\Traits;

use Illuminate\Support\Arr;

trait Listable
{
    public static function get () : array {
        return Arr::map(Arr::pluck(self::cases(), 'name', 'value'), function (string $value, int $key) {
            return ucfirst(strtolower($value));
        });
    }

    public function name () :string {
        return ucfirst(strtolower($this->name));
    }
}
