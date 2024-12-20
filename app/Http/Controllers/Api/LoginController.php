<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\AccountResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController
{
    public function login (LoginRequest $request) : AccountResource|JsonResponse {

        $credentials = $request->validated();

        $user = User::query()
            ->where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return new AccountResource($user);
        }

        return response()->json([
            'message' => __('auth.failed_api')
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function me (Request $request) : AccountResource{
        return new AccountResource($request->user());
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return Response
     */
    public function logout (Request $request): Response {

        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
