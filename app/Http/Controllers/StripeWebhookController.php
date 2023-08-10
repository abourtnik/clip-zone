<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\Premium\Created;
use App\Notifications\Premium\Invoice;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService)
    {
    }

    public function index(Request $request): Response
    {
        Log::channel('stripe')->info($request->get('type'));

        return match ($request->get('type')) {
            'invoice.paid' => $this->onInvoicePaid($request->get('data')['object']),
            'charge.refunded' => $this->onRefund($request->get('data')['object']),
            'customer.subscription.created' => $this->onSubscriptionCreated($request->get('data')['object']),
            'customer.subscription.updated' => $this->onSubscriptionUpdated($request->get('data')['object']),
            'customer.subscription.deleted' => $this->onSubscriptionDeleted($request->get('data')['object']),
            default => response()->noContent()
        };
    }

    public function onInvoicePaid (array $data) : Response  {

        $user = $this->getUserFromStripeId($data['customer']);
        $subscription = Subscription::where('stripe_id', $data['subscription'])->firstOrFail();

        $transaction = Transaction::create([
            'stripe_id' => $data['payment_intent'],
            'amount' => $data['amount_paid'],
            'tax' => $data['vat'] ?? 0,
            'fee' => $data['fee'] ?? 0,
            'date' => Carbon::createFromTimestamp($data['created']),
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'name' => $data['customer_name'],
            'address' => $data['customer_address']['line1'],
            'city' => $data['customer_address']['city'],
            'postal_code' => $data['customer_address']['postal_code'],
            'country' => $data['customer_address']['country'],
        ]);

        $this->invoiceService->generate($transaction);

        $user->notify(new Invoice($transaction));

        return response()->noContent();
    }

    public function onRefund (array $data) : Response  {

        return response()->noContent();
    }

    public function onSubscriptionCreated (array $data) : Response  {

        $user = $this->getUserFromStripeId($data['customer']);
        $plan = $this->getPlanFromStripeId($data['plan']['id']);

        Subscription::create([
            'status' => SubscriptionStatus::ACTIVE,
            'next_payment' => Carbon::createFromTimestamp($data['current_period_end']),
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'stripe_id' => $data['id'],
        ]);

        $user->notify(new Created());

        return response()->noContent();
    }

    public function onSubscriptionUpdated (array $data) : Response  {

        $subscription = Subscription::where('stripe_id', $data['id'])->firstOrFail();
        $user = $this->getUserFromStripeId($data['customer']);

        if ($data['cancel_at_period_end']) {
            $subscription->update(['status' => SubscriptionStatus::INACTIVE]);
        } else {
            $subscription->update([
                'status' => SubscriptionStatus::ACTIVE,
                'next_payment' => Carbon::createFromTimestamp($data['current_period_end'])
            ]);
            $user->update(([
                'premium_end' => Carbon::createFromTimestamp($data['current_period_end'])
            ]));
        }

        return response()->noContent();

    }

    public function onSubscriptionDeleted (array $data) : Response  {

        Log::channel('stripe')->info('---- onSubscriptionDeleted ----');

        return response()->noContent();

    }

    private function getUserFromStripeId ($id) : User {
        return User::where('stripe_id', $id)->firstOrFail();
    }

    private function getPlanFromStripeId ($id) : Plan {
        return Plan::where('stripe_id', $id)->firstOrFail();
    }
}
