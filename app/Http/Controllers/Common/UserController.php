<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\User\UserUpdateRequest;
use App\Models\User;
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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return response(User::getFromRequest($request->user()));
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
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function show(Request $request, User $user): Response
    {
        return response($user);
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
        $user->updateFromRequest($request->validated());
        return response($user);
    }

    /**
     *
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws \Exception
     */
    public function destroy(Request $request, User $user): Response
    {
        $user->deleteFromRequest();
        return response(null, 204);
    }
}
