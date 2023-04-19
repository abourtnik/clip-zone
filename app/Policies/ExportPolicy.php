<?php

namespace App\Policies;

use App\Models\Export;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ExportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can download export.
     *
     * @param User $user
     * @param Export $export
     * @return Response|bool
     */
    public function download(User $user, Export $export): Response|bool
    {
        return $export->is_completed
            ? Response::allow()
            : Response::denyWithStatus(404, 'Export is not ready');
    }
}
