<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum ExportStatus : int {

    case PENDING = 0;
    case COMPLETED = 1;
    case ERROR = 2;

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
            self::COMPLETED => 'success',
            self::ERROR => 'danger',
        };
    }

    public function icon(): string
    {
        return match($this)
        {
            self::PENDING => 'clock',
            self::COMPLETED => 'check',
            self::ERROR => 'x-mark',
        };
    }

    public function name () :string {
        return ucfirst(strtolower($this->name));
    }
}
