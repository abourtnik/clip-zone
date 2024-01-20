<?php

namespace App\Filters\Drivers;

abstract class DriverFilter
{
    public function receivedFilters(): array
    {
        $filters = request()->all();

        $clear = request()->exists('clear');
        return $clear ? [] : request()->only(array_keys($filters));
    }
}
