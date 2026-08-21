<?php

namespace App\Filters\Traits;

use App\Enums\DateRange;
use Carbon\Constants\UnitValue;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;

trait DateFilter
{
    protected function getDateField(): string|Expression
    {
        return $this->dateField;
    }

    public function date (string $date): Builder
    {
        $dateField = $this->getDateField();

        $now = now();

        return match ($date) {
            DateRange::TODAY->value => $this->builder->whereToday($dateField),
            DateRange::LAST_24_HOURS->value => $this->builder->where($dateField, '>=', $now->copy()->subHours(24)),
            DateRange::THIS_WEEK->value => $this->builder->whereBetween($dateField, [
                $now->copy()->startOfWeek(UnitValue::MONDAY),
                $now->copy()->endOfWeek(UnitValue::SUNDAY),
            ]),
            DateRange::LAST_7_DAYS->value => $this->builder->where($dateField, '>=', $now->copy()->subDays(7)),
            DateRange::THIS_MONTH->value => $this->builder->whereBetween($dateField, [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ]),
            DateRange::LAST_30_DAYS->value => $this->builder->where($dateField, '>=', $now->copy()->subDays(30)),
            DateRange::LAST_90_DAYS->value => $this->builder->where($dateField, '>=', $now->copy()->subDays(90)),
            DateRange::THIS_YEAR->value => $this->builder->whereBetween($dateField, [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
            ]),
            DateRange::LAST_6_MONTHS->value => $this->builder->where($dateField, '>=', $now->copy()->subMonths(6)),
            DateRange::LAST_12_MONTHS->value => $this->builder->where($dateField, '>=', $now->copy()->subMonths(12)),
            default => $this->builder,
        };
    }
}
