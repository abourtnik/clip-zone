<?php

namespace App\Enums;

use App\Enums\Traits\Listable;

enum ReportStatus : int {

    use Listable;

    case PENDING = 0;
    case BLOCKED = 1;
    case CANCELLED = 2;

    public function color(): string
    {
        return match($this)
        {
            self::PENDING => 'warning',
            self::BLOCKED => 'danger',
            self::CANCELLED => 'secondary',
        };
    }
}
