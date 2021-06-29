<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\User\MyUserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MyUserController extends Controller
{
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
    public function index(Request $request): Response
    {
        return response($request->user());
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
    public function store(MyUserUpdateRequest $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $result = $user->updateFromRequest($request->validated());
        return response($result);
    }
}
