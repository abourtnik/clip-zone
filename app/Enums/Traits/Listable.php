<?php

namespace App\Enums\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;


trait Listable
{
    public static function get () : array {
        return Arr::map(Arr::pluck(self::cases(), 'name', 'value'), function (string $value, int|string $key) {
            return __(Str::ucfirst(Str::lower(Str::replace('_', ' ', $value))));
        });
    }

    public function name () :string {
        return __(Str::ucfirst(Str::lower(Str::replace('_', ' ', $this->name))));
    }
}
