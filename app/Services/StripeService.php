<?php


namespace App\Services;

use App\Data\StripeCard;
use Laravel\Cashier\Cashier;
use Stripe\StripeObject;

class StripeService
{
    public function getCustomerCard(string $stripeUserId): StripeCard
    {
        $card = $this->retrieveCustomerCard($stripeUserId);

        return new StripeCard(
            $card->brand,
            $card->last4,
            $card->exp_month,
            $card->exp_year
        );
    }

    public function getTransactionFee(string $stripeChargeId): int
    {
        $charge = Cashier::stripe()->charges->retrieve($stripeChargeId,
            ['expand' => ['balance_transaction']]
        );

        return $charge->balance_transaction->fee;
    }

    private function retrieveCustomerCard(string $stripeUserId): StripeObject
    {
        $customer = Cashier::stripe()->customers->retrieve($stripeUserId, [
            'expand' => ['invoice_settings.default_payment_method'],
        ]);

        // 1. Customer's default card, if set
        if ($pm = $customer->invoice_settings->default_payment_method) {
            return $pm->card;
        }

        // 2. Subscription's card (typical case after Checkout)
        $subscriptions = Cashier::stripe()->subscriptions->all([
            'customer' => $stripeUserId,
            'status' => 'all',
            'limit' => 1,
            'expand' => ['data.default_payment_method'],
        ]);

        if ($pm = $subscriptions->data[0]->default_payment_method ?? null) {
            return $pm->card;
        }

        // 3. Last resort: the most recent card
        $methods = Cashier::stripe()->paymentMethods->all([
            'customer' => $stripeUserId,
            'type' => 'card',
            'limit' => 1,
        ]);

        return $methods->data[0]->card;
    }
}
