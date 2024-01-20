<?php

namespace App\Filters\Drivers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MySQLFilter extends DriverFilter
{
    /**
     * The field to use for date filter.
     *
     * @var string
     */
    protected string $dateField = 'created_at';

    /**
     * The builder instance.
     *
     * @var Builder
     */
    protected Builder $builder;

    public function apply(Builder $builder) : Builder
    {
        foreach ($this->receivedFilters() as $name => $value) {

            $this->builder = $builder;

            $method = Str::camel($name);

            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], [$value]);
            }
        }

        return $builder;
    }
}
