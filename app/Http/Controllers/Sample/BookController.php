<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Book\BookCreateRequest;
use App\Http\Requests\Sample\Book\BookIndexRequest;
use App\Http\Requests\Sample\Book\BookShowRequest;
use App\Http\Requests\Sample\Book\BookUpdateRequest;
use App\Models\Sample\Book;
use Exception;
use Illuminate\Http\Response;
use App\Models\User;

class BookController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Book::class, 'book');
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
    public function index(BookIndexRequest $request): Response
    {
        $books = Book::allOrWhereUserId($request->user());
        return response($books);
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
     * @param BookCreateRequest $request
     * @return Response
     */
    public function store(BookCreateRequest $request): Response
    {
        $book = Book::createWithUser($request->all(), $request->user());
        return response($book);
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
     * @param BookShowRequest $request
     * @param Book $book
     * @return Response
     */
    public function show(BookShowRequest $request, Book $book): Response
    {
        return response($book);
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
     * @param BookUpdateRequest $request
     * @param Book $book
     * @return Response
     */
    public function update(BookUpdateRequest $request, Book $book): Response
    {
        $book->update($request->all());
        return response($book);
    }

    /**
     * @param BookShowRequest $request
     * @param Book $book
     * @return Response
     * @throws Exception
     */
    public function destroy(BookShowRequest $request, Book $book): Response
    {
        $book->delete();
        return response(null, 204);
    }
}
