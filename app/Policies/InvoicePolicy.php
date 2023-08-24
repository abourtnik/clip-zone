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
     * Perform pre-authorization checks.
     *
     * @param  User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, string $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }


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
