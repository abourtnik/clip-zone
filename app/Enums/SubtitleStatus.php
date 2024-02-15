<?php

namespace App\Enums;

use App\Enums\Traits\Listable;

enum SubtitleStatus : int {

    use Listable;

    case PUBLIC = 0;
    case DRAFT = 1;

    public function color(): string
    {
        return match($this)
        {
            self::PUBLIC => 'success',
            self::DRAFT => 'secondary',
        };
    }

    public function icon(): string
    {
        return match($this)
        {
            self::PUBLIC => 'earth-europe',
            self::DRAFT => 'file',
        };
    }
}
