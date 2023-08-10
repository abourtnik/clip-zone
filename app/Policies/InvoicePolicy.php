<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view invoice.
     *
     * @param  User $user
     * @param  Transaction $transaction
     * @return Response|bool
     */
    public function show(User $user, Transaction $transaction): Response|bool
    {
        return $transaction->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }
}
