<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DevicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the device.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function update(User $user, Device $device): Response|bool
    {
        return $device->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can delete the device.
     *
     * @param User $user
     * @param Device $device
     * @return Response|bool
     */
    public function delete(User $user, Device $device): Response|bool
    {
        return $device->user()->is($user)
            ? Response::allow()
            : Response::denyWithStatus(404);
    }
}
