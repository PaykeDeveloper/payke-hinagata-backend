<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return [
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
            'showMe' => 'showMe',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'create', 'store', 'showMe'];
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "name": "Queen Gusikowski DDS",
     * "email": "josianne.mcglynn@example.net",
     * "email_verified_at": "2021-03-25T01:48:01.000000Z",
     * "two_factor_secret": null,
     * "two_factor_recovery_codes": null,
     * "created_at": "2021-03-25T01:48:01.000000Z",
     * "updated_at": "2021-03-25T01:48:01.000000Z"
     * }
     * ]
     *
     * @param UserIndexRequest $request
     * @return Response
     */
    public function index(UserIndexRequest $request): Response
    {
        return response(User::allOrWhereId($request->user()));
    }

    /**
     * @response {
     * "id": 1,
     * "name": "Queen Gusikowski DDS",
     * "email": "josianne.mcglynn@example.net",
     * "email_verified_at": "2021-03-25T01:48:01.000000Z",
     * "two_factor_secret": null,
     * "two_factor_recovery_codes": null,
     * "created_at": "2021-03-25T01:48:01.000000Z",
     * "updated_at": "2021-03-25T01:48:01.000000Z"
     * }
     *
     * @param UserShowRequest $request
     * @param User $user
     * @return Response
     */
    public function show(UserShowRequest $request, User $user = null): Response
    {
        if ($user) {
            // permissions の取得を行うと自動的にレスポンスに挿入される (permissions key)
            $user->getDirectPermissions();
            $response = $user;
        } else {
            // permissions の取得を行うと自動的にレスポンスに挿入される (permissions key)
            $request->user()->getDirectPermissions();
            $response = $request->user();
        }

        return response($response);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return Response
     */
    public function update(UserUpdateRequest $request, User $user): Response
    {
        // Role の更新
        $roles = $request->input('roles');
        if (!is_null($roles)) {
            $user->syncRoles($roles);
        }

        $user->update($request->validated());
        return response($user);
    }
}
