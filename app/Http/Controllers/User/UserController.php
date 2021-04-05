<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Models\Sample\Book;
use Exception;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(User::class, 'user');
    }

    /**
     * @response [
     * {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     * ]
     *
     * @param BookIndexRequest $request
     * @return Response
     */
    public function index(UserIndexRequest $request): Response
    {
        // 自分自身
        // return response($request->user());

        return response(User::all());
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
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param BookShowRequest $request
     * @param Book $book
     * @return Response
     */
    public function show(UserShowRequest $request, User $user): Response
    {
        return response($user);
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
