<?php

namespace App\Http\Controllers\Api\Private;

use App\Events\UserSubscribed;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscribeController
{
    public function subscribe (Request $request, User $user) : Response {
        $subscription = $request->user()->subscriptions()->toggle($user);
        UserSubscribed::dispatchIf($subscription['attached'], $user, $request->user());
        return response()->noContent();
    }

}
