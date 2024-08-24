<?php

namespace App\Enums\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;


trait Listable
{
    public static function get () : array {
        return Arr::map(Arr::pluck(self::cases(), 'name', 'value'), function (string $value, int $key) {
            return Str::headline(strtolower($value));
        });
    }

    public function name () :string {
        return ucfirst(strtolower($this->name));
    }
}
