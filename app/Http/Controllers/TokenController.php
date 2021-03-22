<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\LoginRequest;

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
        Fortify::authenticateThrough(function (Request $request) {
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                Features::enabled(Features::twoFactorAuthentication()) ?
                    RedirectIfTwoFactorAuthenticatable::class : null,
                AttemptToAuthenticate::class,
//                PrepareAuthenticatedSession::class,
            ]);
        });

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
