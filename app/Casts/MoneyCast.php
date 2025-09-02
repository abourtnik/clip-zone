<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Number;


class MoneyCast implements CastsAttributes
{

    protected int $precision;
    protected string $currency;
    protected string $localeAttribute;

    /**
     * Money constructor.
     * @param int $precision
     * @param string $currency
     * @param string $localeAttribute
     */
    public function __construct(int $precision = 2, string $currency = 'EUR', string $localeAttribute = 'country')
    {
        $this->precision = $precision;
        $this->currency = $currency;
        $this->localeAttribute = $localeAttribute;
    }


    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): false|string
    {
        return Number::currency(($value / 100), $this->currency, $attributes[$this->localeAttribute] ?? App::currentLocale(), $this->precision);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
