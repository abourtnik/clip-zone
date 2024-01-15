<?php

namespace App\Filters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DateFilter
{
    public function dateStart (string $date): Builder
    {
        return $this->builder->where($this->dateField, '>=', $date);
    }

    public function dateEnd (string $date): Builder
    {
        return $this->builder->where($this->dateField, '<=', $date);
    }
}
