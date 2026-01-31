<?php

namespace App\Data;

final readonly class StripeCard
{
    public function __construct(
        public string $brand,
        public string $last4,
        public int $expMonth,
        public int $expYear,
    ) {}
}
