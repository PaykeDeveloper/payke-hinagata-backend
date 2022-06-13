<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\Common\Token\TokenCreateRequest;
use App\Http\Resources\Common\TokenResource;
use App\Repositories\Common\TokenRepository;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

/**
 * @group Common Token
 */
class TokenController extends AuthenticatedSessionController
{
    private TokenRepository $repository;

    public function __construct(StatefulGuard $guard, TokenRepository $repository)
    {
        parent::__construct($guard);
        $this->repository = $repository;
    }

    /**
     * @unauthenticated
     * @response {
     * "token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
     * }
     */
    public function storeToken(TokenCreateRequest $request): mixed
    {
        return $this->loginPipeline($request)->then(function ($request) {
            $resource = $this->repository->store($request->validated(), $request->user());
            return TokenResource::make($resource);
        });
    }

    public function destroyToken(Request $request): LogoutResponse
    {
        $this->repository->delete($request->user(), $request->bearerToken());
        $this->guard->logout();
        return app(LogoutResponse::class);
    }
}
