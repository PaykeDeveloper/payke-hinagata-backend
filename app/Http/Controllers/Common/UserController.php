<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\User\UserUpdateRequest;
use App\Http\Resources\Common\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Common User
 */
class UserController extends Controller
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->authorizeResource(User::class);
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "name": "Kitty Emmerich I",
     * "email": "yrath@example.com",
     * "email_verified_at": "2022-06-13T03:55:23.000000Z",
     * "locale": null,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "role_names": [
     * "Manager"
     * ],
     * "created_at": "2022-06-13T03:55:23.000000Z"
     * }
     * ]
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $resources = $this->repository->index($request->user());
        return UserResource::collection($resources);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "name": "Kitty Emmerich I",
     * "email": "yrath@example.com",
     * "email_verified_at": "2022-06-13T03:55:23.000000Z",
     * "locale": null,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "role_names": [
     * "Manager"
     * ],
     * "created_at": "2022-06-13T03:55:23.000000Z"
     * }
     */
    public function show(Request $request, User $user): UserResource
    {
        return UserResource::make($user);
    }

    /**
     * @response
     * {
     * "id": 1,
     * "name": "Kitty Emmerich I",
     * "email": "yrath@example.com",
     * "email_verified_at": "2022-06-13T03:55:23.000000Z",
     * "locale": null,
     * "permission_names": [
     * "view_all__user"
     * ],
     * "role_names": [
     * "Manager"
     * ],
     * "created_at": "2022-06-13T03:55:23.000000Z"
     * }
     */
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $resource = $this->repository->update($request->validated(), $user);
        return UserResource::make($resource);
    }

    public function destroy(Request $request, User $user): Response
    {
        $user->delete();
        return response()->noContent();
    }
}
