<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
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
        Log::channel('stripe')->info($request->get('type'));

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

        if ($data['amount_paid'] !== 0) {

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
        }


        return response()->noContent();
    }

    public function onInvoiceUnPaid (array $data) : Response  {

        $user = $this->getUserFromStripeId($data['customer']);

        $user->notify(new Unpaid($data['amount_due']));

        return response()->noContent();
    }

    public function onSubscriptionCreated (array $data) : Response  {

        $user = $this->getUserFromStripeId($data['customer']);
        $plan = $this->getPlanFromStripeId($data['plan']['id']);

        $paymentMethod = Cashier::stripe()->paymentMethods->retrieve($data['default_payment_method']);

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

        $user->notify(new Welcome());

        return response()->noContent();
    }

    public function onSubscriptionUpdated (array $data) : Response  {

        $subscription = Subscription::where('stripe_id', $data['id'])->firstOrFail();

        $subscription->update([
            'stripe_status' => $data['status']
        ]);

        if ($data['cancel_at_period_end']) {
            $subscription->update([
                'ends_at' => $subscription->on_trial
                    ? $subscription->trial_ends_at
                    : Carbon::createFromTimestamp($data['current_period_end'])
            ]);
        } else {
            $subscription->update([
                'next_payment' => Carbon::createFromTimestamp($data['current_period_end']),
                'ends_at' => null
            ]);
        }

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

        Subscription::where('stripe_id', $data['id'])->delete();

        return response()->noContent();
    }

    private function getUserFromStripeId (string $id) : User {
        return User::where('stripe_id', $id)->firstOrFail();
    }

    private function getPlanFromStripeId (string $id) : Plan {
        return Plan::where('stripe_id', $id)->firstOrFail();
    }
}
