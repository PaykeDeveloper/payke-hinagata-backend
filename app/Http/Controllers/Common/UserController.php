<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\User\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * @response [
     * {
     * "id": 4,
     * "name": "user01",
     * "email": "user01@example.com",
     * "email_verified_at": "2021-04-19T09:32:02.000000Z",
     * "locale": null,
     * "created_at": "2021-04-19T09:32:01.000000Z",
     * "updated_at": "2021-04-19T09:32:02.000000Z",
     * "permission_names": [
     * "division_viewOwn",
     * "division_createOwn",
     * "division_updateOwn",
     * "division_deleteOwn"
     * ],
     * "role_names": [
     * "Staff"
     * ]
     * }
     * ]
     */
    public function index(Request $request): Response
    {
        return response(User::getFromRequest($request->user()));
    }

    /**
     * @response {
     * "id": 4,
     * "name": "user01",
     * "email": "user01@example.com",
     * "email_verified_at": "2021-04-19T09:32:02.000000Z",
     * "locale": null,
     * "created_at": "2021-04-19T09:32:01.000000Z",
     * "updated_at": "2021-04-19T09:32:02.000000Z",
     * "permission_names": [
     * "division_viewOwn",
     * "division_createOwn",
     * "division_updateOwn",
     * "division_deleteOwn"
     * ],
     * "role_names": [
     * "Staff"
     * ]
     * }
     */
    public function show(Request $request, User $user): Response
    {
        return response($user);
    }

    /**
     * @response {
     * "id": 4,
     * "name": "user01",
     * "email": "user01@example.com",
     * "email_verified_at": "2021-04-19T09:32:02.000000Z",
     * "locale": null,
     * "created_at": "2021-04-19T09:32:01.000000Z",
     * "updated_at": "2021-04-19T09:32:02.000000Z",
     * "permission_names": [
     * "division_viewOwn",
     * "division_createOwn",
     * "division_updateOwn",
     * "division_deleteOwn"
     * ],
     * "role_names": [
     * "Staff"
     * ]
     * }
     */
    public function update(UserUpdateRequest $request, User $user): Response
    {
        $updated_user = $user->updateFromRequest($request->validated());
        return response($updated_user);
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request, User $user): Response
    {
        $user->deleteFromRequest();
        return response(null, 204);
    }
}
