<?php

namespace App\Filters\Drivers;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class MeilisearchFilter extends DriverFilter
{
    public function apply() : string
    {
        $filters = [];

        foreach ($this->receivedFilters() as $name => $value) {

            $method = Str::camel($name);

            if (method_exists($this, $method)) {
                $filters[] = call_user_func_array([$this, $method], [$value]);
            }
        }

        return Arr::join($filters, ' AND ');
    }
}
