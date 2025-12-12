<?php

namespace App\Policies;

use App\Models\User;
use App\Models\View;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ViewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param View $view
     * @return Response|bool
     */
    public function clear(User $user, View $view) : Response|bool
    {
        return $view->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not authorized to clear this view');
    }
}
