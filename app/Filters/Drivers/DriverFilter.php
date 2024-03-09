<?php

namespace App\Filters\Drivers;

abstract class DriverFilter
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function receivedFilters(): array
    {
        $filters = $this->filters ?: request()->query();

        $clear = request()->exists('clear');

        return $clear ? [] : $filters;
    }
}
