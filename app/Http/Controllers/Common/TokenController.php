<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\Common\Token\TokenCreateRequest;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class TokenController extends AuthenticatedSessionController
{
    private const TOKEN_NAME = 'api_v1';

    /**
     * @unauthenticated
     * @response {
     * "token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
     * }
     */
    public function storeToken(TokenCreateRequest $request): mixed
    {
        return $this->loginPipeline($request)->then(function ($request) {
            $user = $request->user();
            $token_key = $this->getTokenKey($request);
            $user->tokens()->where('name', $token_key)->delete();
            $token = $user->createToken($token_key)->plainTextToken;
            return response(['token' => $token]);
        });
    }

    public function destroyToken(Request $request): LogoutResponse
    {
        $user = $request->user();
        $user->tokens()->where('token', $request->bearerToken())->delete();

        $this->guard->logout();
        return app(LogoutResponse::class);
    }

    private function getTokenKey(TokenCreateRequest $request): string
    {
        return implode('|', [
            self::TOKEN_NAME,
            $request->package_name,
            $request->platform_type,
            $request->devide_id,
        ]);
    }
}
