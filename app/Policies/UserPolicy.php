<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function viewAny(User $user) : Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function view(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view video.
     *
     * @param User|null $user
     * @return Response|bool
     */
    public function show(?User $user, User $model): Response|bool
    {
        return $model->is_active
            ? Response::allow()
            : Response::denyWithStatus(404, 'This user don\'t exist');
    }

    /**
     * Determine whether the user can create the model.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function create(User $user) : Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function update(User $user) : Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function delete(User $user) : Response|bool
    {
        return true;
    }
}
