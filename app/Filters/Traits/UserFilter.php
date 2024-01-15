<?php

namespace App\Filters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait UserFilter
{
    public function user(string $value): Builder
    {
        return $this->builder->whereRelation('user', 'id', $value);
    }
}
