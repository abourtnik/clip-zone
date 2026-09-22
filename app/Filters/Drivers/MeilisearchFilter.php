<?php

namespace App\Filters\Drivers;

use Illuminate\Support\Str;

class MeilisearchFilter extends DriverFilter
{
    public function apply() : array
    {
        $filters = [];

        foreach ($this->receivedFilters() as $name => $value) {

            $method = Str::camel($name);

            if (method_exists($this, $method)) {
                $filters[] = call_user_func_array([$this, $method], [$value]);
            }
        }

        return $filters;
    }
}
