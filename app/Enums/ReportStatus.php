<?php

namespace App\Enums;

use App\Enums\Traits\Listable;
use Illuminate\Support\Arr;

enum ReportStatus : int {

    use Listable;

    case PENDING = 0;
    case ACCEPTED = 1;
    case REJECTED = 2;

    public function color(): string
    {
        return match($this)
        {
            self::PENDING => 'warning',
            self::ACCEPTED => 'success',
            self::REJECTED => 'danger',
        };
    }
}
