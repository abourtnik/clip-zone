<?php


namespace App\Services;

use App\Data\StripeCard;
use Laravel\Cashier\Cashier;

class StripeService
{
    public function getUserCard(string $stripeUserId): StripeCard
    {
        $user = Cashier::stripe()->customers->retrieve($stripeUserId);

        $card = Cashier::stripe()->paymentMethods->retrieve($user->invoice_settings->default_payment_method)->card;

        return new StripeCard(
            $card->brand,
            $card->last4,
            $card->exp_month,
            $card->exp_year,
        );
    }
    public function getTransactionFee(string $stripeChargeId): int
    {
        $charge = Cashier::stripe()->charges->retrieve($stripeChargeId,
            ['expand' => ['balance_transaction']]
        );

        return $charge->balance_transaction->fee;
    }
}
