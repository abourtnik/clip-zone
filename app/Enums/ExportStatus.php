<?php

namespace App\Enums;

use App\Enums\Traits\Listable;

enum ExportStatus : int {

    use Listable;

    case PENDING = 0;
    case COMPLETED = 1;
    case ERROR = 2;

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
            self::ERROR => 'circle-exclamation',
        };
    }
}
