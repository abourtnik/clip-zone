<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Filter
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

    public function receivedFilters(): array
    {
        $filters = request()->all();

        $clear = request()->exists('clear');
        return $clear ? [] : request()->only(array_keys($filters));
    }
}
