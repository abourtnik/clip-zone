<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum ReportStatus : int {

    case PENDING = 0;
    case ACCEPTED = 1;
    case REJECTED = 2;

    public static function get () : array {
        return Arr::map(Arr::pluck(self::cases(), 'name', 'value'), function (string $value, int $key) {
            return ucfirst(strtolower($value));
        });
    }

    public function color(): string
    {
        return match($this)
        {
            self::PENDING => 'warning',
            self::ACCEPTED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function name () :string {
        return ucfirst(strtolower($this->name));
    }
}
