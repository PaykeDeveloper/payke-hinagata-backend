<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserShowMeRequest;
use Illuminate\Http\Response;
use App\Models\User;

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

    // /**
    //  * @response {
    //  * "id": 2,
    //  * "user_id": 1,
    //  * "title": "Title 1",
    //  * "author": "Author 1",
    //  * "release_date": "2021-03-16",
    //  * "created_at": "2021-03-05T08:31:33.000000Z",
    //  * "updated_at": "2021-03-05T08:31:33.000000Z"
    //  * }
    //  *
    //  * @param BookCreateRequest $request
    //  * @return Response
    //  */
    // public function store(BookCreateRequest $request): Response
    // {
    //     $book = Book::createWithUser($request->all(), $request->user());
    //     return response($book);
    // }

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
     * @param BookShowRequest $request
     * @param User $user
     * @return Response
     */
    public function show(UserShowRequest $request, User $user = null): Response
    {
        return response($user ?? $request->user());
    }

    /**
     * @response {
     * "id": 2,
     * "name": "Prof. Dee Hamill MD",
     * "email": "demario81@example.com",
     * "email_verified_at": "2021-03-26T06:52:06.000000Z",
     * "two_factor_secret": null,
     * "two_factor_recovery_codes": null,
     * "created_at": "2021-03-26T06:52:06.000000Z",
     * "updated_at": "2021-03-26T06:52:06.000000Z",
     * "roles": []
     * }
     *
     * @param UserShowMeRequest $request
     * @return Response
     */
    public function showMe(UserShowMeRequest $request): Response
    {
        return response($request->user());
    }

    // /**
    //  * @response {
    //  * "id": 2,
    //  * "user_id": 1,
    //  * "title": "Title 1",
    //  * "author": "Author 1",
    //  * "release_date": "2021-03-16",
    //  * "created_at": "2021-03-05T08:31:33.000000Z",
    //  * "updated_at": "2021-03-05T08:31:33.000000Z"
    //  * }
    //  *
    //  * @param BookUpdateRequest $request
    //  * @param Book $book
    //  * @return Response
    //  */
    // public function update(BookUpdateRequest $request, Book $book): Response
    // {
    //     $book->update($request->all());
    //     return response($book);
    // }

    // /**
    //  * @param BookShowRequest $request
    //  * @param Book $book
    //  * @return Response
    //  * @throws Exception
    //  */
    // public function destroy(BookShowRequest $request, Book $book): Response
    // {
    //     $book->delete();
    //     return response(null, 204);
    // }
}
