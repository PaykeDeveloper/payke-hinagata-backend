<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
 *
 * @package App\Http\Controllers\Common
 */
class MyUserController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return response($request->user());
    }
}
