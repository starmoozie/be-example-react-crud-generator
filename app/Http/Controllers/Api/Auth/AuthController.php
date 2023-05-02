<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\HttpCode;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\Resource as UserResource;
use App\Models\User;

class AuthController extends Controller
{
    use \App\Traits\ResponseMessageTrait;

    public function login(LoginRequest $request)
    {
        $payload = $request->only(['email', 'password']);

        return $this->forceLogin($payload);
    }

    public function register(RegisterRequest $request)
    {
        User::create(
            $request->only((new User)->getFillable())
        );

        $request->merge(['password' => $request->password_confirmation]);

        $payload = $request->only(['email', 'password']);

        return $this->forceLogin($payload);
    }

    public function forceLogin($payload)
    {
        // Check auth login
        if (!\Auth::validate($payload)) {
            return $this->fails(__('auth.failed'), HttpCode::UNPROCESSABLE_ENTITY);
        }

        // Auth login
        $entry = \Auth::getProvider()->retrieveByCredentials($payload);

        // Delete old token
        $entry->tokens()->delete();

        // Create new token
        $entry->token = $entry->createToken('token')->accessToken;

        return new UserResource($this->forceLogin($entry));
    }
}
