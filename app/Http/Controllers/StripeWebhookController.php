<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\Premium\Cancel;
use App\Notifications\Premium\Invoice;
use App\Notifications\Premium\Unpaid;
use App\Notifications\Premium\Welcome;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;

class StripeWebhookController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService)
    {
    }

    public function index(Request $request): Response
    {
        Log::channel('stripe')->info($request->get('type'). ' ('.$request->get('id').')');

        $request->headers->set('Accept', 'application/json');

        return match ($request->get('type')) {
            'invoice.payment_succeeded' => $this->onInvoicePaid($request->get('data')['object']),
            'invoice.payment_failed' => $this->onInvoiceUnPaid($request->get('data')['object']),
            'customer.subscription.created' => $this->onSubscriptionCreated($request->get('data')['object']),
            'customer.subscription.updated' => $this->onSubscriptionUpdated($request->get('data')['object']),
            'customer.subscription.deleted' => $this->onSubscriptionDeleted($request->get('data')['object']),
            'customer.updated' => $this->onCustomerUpdated($request->get('data')['object']),
            default => response()->noContent()
        };
    }

    public function onInvoicePaid (array $data) : Response  {

        if ($data['amount_paid'] === 0) {
            return response()->noContent();
        }

        // For API-version 2025-10-29.clover
        // $subscriptionId = $data['parent']['subscription_details']['subscription'];

        $user = $this->getUserFromStripeId($data['customer']);
        $subscription = Subscription::where('stripe_id', $data['subscription'])->firstOrFail();

        // Get Stripe Fee
        $charge = Cashier::stripe()->charges->retrieve($data['charge'],
            ['expand' => ['balance_transaction']]
        );

        $transaction = Transaction::create([
            'stripe_id' => $data['payment_intent'],
            'amount' => $data['amount_paid'],
            'tax' => $data['tax'] ?? 0,
            'fee' => $charge->balance_transaction->fee,
            'date' => Carbon::createFromTimestamp($data['created']),
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'name' => $data['customer_name'],
            'address' => $data['customer_address']['line1'] . ' ' .$data['customer_address']['line2'],
            'city' => $data['customer_address']['city'],
            'postal_code' => $data['customer_address']['postal_code'],
            'country' => $data['customer_address']['country'],
            'vat_id' => $data['customer_tax_ids'] ? $data['customer_tax_ids'][0]['value'] : null,
        ]);

        $this->invoiceService->generate($transaction);

        $user->notify(new Invoice($transaction));

        return response()->noContent();
    }

    public function onInvoiceUnPaid (array $data) : Response  {

        $user = $this->getUserFromStripeId($data['customer']);

        if ($user->is_premium) {
            $user->notify(new Unpaid($data['amount_due']));
        }

        return response()->noContent();
    }

    public function onSubscriptionCreated (array $data) : Response  {

        $user = User::where('stripe_id', $data['customer'])->firstOrFail();

        $plan = Plan::where('stripe_id', $data['plan']['id'])->firstOrFail();

        $default_payment_method = Cashier::stripe()->subscriptions->retrieve($data['id'])->default_payment_method;

        $paymentMethod = Cashier::stripe()->paymentMethods->retrieve($default_payment_method);

        // User Cancel Subscription and Renew
        if ($user->premium_subscription) {
            $user->premium_subscription()->update([
                'next_payment' => Carbon::createFromTimestamp($data['current_period_end']),
                'stripe_status' => $data['status'],
                'plan_id' => $plan->id,
                'stripe_id' => $data['id'],
                'ends_at' => null,
                'card_last4' => $paymentMethod->card->last4,
                'card_expired_at' => Carbon::createFromDate($paymentMethod->card->exp_year, $paymentMethod->card->exp_month)->endOfMonth()
            ]);
        }

        else {
            Subscription::create([
                'next_payment' => Carbon::createFromTimestamp($data['current_period_end']),
                'stripe_status' => $data['status'],
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'stripe_id' => $data['id'],
                'trial_ends_at' => Carbon::createFromTimestamp($data['trial_end']),
                'card_last4' => $paymentMethod->card->last4,
                'card_expired_at' => Carbon::createFromDate($paymentMethod->card->exp_year, $paymentMethod->card->exp_month)->endOfMonth()
            ]);
        }

        $user->load('premium_subscription');

        $user->notify(new Welcome());

        return response()->noContent();
    }

    public function onSubscriptionUpdated (array $data) : Response  {

        $subscription = Subscription::where('stripe_id', $data['id'])->firstOrFail();

        $subscription->update([
            'stripe_status' => $data['status']
        ]);

        // For API-version 2025-10-29.clover
        // $firstItem = $data['items']['data'][0];
        // $firstItem['current_period_end']

        if ($data['cancel_at_period_end']) {
            $subscription->update([
                'ends_at' => $subscription->on_trial
                    ? $subscription->trial_ends_at
                    : Carbon::createFromTimestamp($data['current_period_end'])
            ]);

            return response()->noContent();
        }

        $subscription->update([
            'next_payment' => Carbon::createFromTimestamp($data['current_period_end']),
            'ends_at' => null
        ]);

        return response()->noContent();
    }

    public function onCustomerUpdated (array $data) : Response  {

        $user = $this->getUserFromStripeId($data['id']);

        $subscription = Subscription::where('user_id', $user->id)->first();

        if ($subscription && $data['invoice_settings']['default_payment_method']) {
            $paymentMethod = Cashier::stripe()->paymentMethods->retrieve($data['invoice_settings']['default_payment_method']);

            $subscription->update([
                'card_last4' => $paymentMethod->card->last4,
                'card_expired_at' => Carbon::createFromDate($paymentMethod->card->exp_year, $paymentMethod->card->exp_month)->endOfMonth()
            ]);
        }

        return response()->noContent();
    }

    public function onSubscriptionDeleted (array $data) : Response  {

        $subscription = Subscription::where('stripe_id', $data['id'])->firstOrFail();

        $subscription->update([
            'stripe_status' => $data['status']
        ]);

        $subscription->user->notify(new Cancel());

        return response()->noContent();
    }

    private function getUserFromStripeId (string $id) : User {
        return User::where('stripe_id', $id)->firstOrFail();
    }
}
