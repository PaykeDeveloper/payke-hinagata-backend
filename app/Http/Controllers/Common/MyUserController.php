<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\User\MyUserUpdateRequest;
use App\Http\Resources\Common\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

/**
 * @group Common MyUser
 */
class MyUserController extends Controller
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @response
     * {
     * "id": 3,
     * "name": "admin",
     * "email": "admin@example.com",
     * "email_verified_at": "2022-06-13T06:02:10.000000Z",
     * "locale": null,
     * "permission_names": [
     * "user__view_all"
     * ],
     * "role_names": [
     * "Administrator"
     * ],
     * "created_at": "2022-06-13T06:02:10.000000Z"
     * }
     */
    public function index(Request $request): UserResource
    {
        /** @var User $resource */
        $resource = $request->user();
        return UserResource::make($resource);
    }

    /**
     * @response
     * {
     * "id": 3,
     * "name": "admin",
     * "email": "admin@example.com",
     * "email_verified_at": "2022-06-13T06:02:10.000000Z",
     * "locale": null,
     * "permission_names": [
     * "user__view_all"
     * ],
     * "role_names": [
     * "Administrator"
     * ],
     * "created_at": "2022-06-13T06:02:10.000000Z"
     * }
     */
    public function store(MyUserUpdateRequest $request): UserResource
    {
        $resource = $this->repository->update($request->validated(), $request->user());
        return UserResource::make($resource);
    }
}
