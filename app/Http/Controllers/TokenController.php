<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\LoginRequest;

/**
 * @group Authenticat User
 */
class TokenController extends AuthenticatedSessionController
{
    private const TOKEN_NAME = 'api_v1';

    /**
     * @unauthenticated
     * @response {
     * "token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
     * }
     *
     * @param LoginRequest $request
     * @return mixed
     */
    public function store(LoginRequest $request)
    {
        return $this->loginPipeline($request)->then(function ($request) {
            $user = $request->user();
            $user->tokens()->where('name', self::TOKEN_NAME)->delete();
            $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
            return response(['token' => $token]);
        });
    }

    /**
     * @param Request $request
     * @return LogoutResponse
     */
    public function destroy(Request $request): LogoutResponse
    {
        $user = $request->user();
        $user->tokens()->where('name', self::TOKEN_NAME)->delete();

        $this->guard->logout();
        return app(LogoutResponse::class);
    }
}
