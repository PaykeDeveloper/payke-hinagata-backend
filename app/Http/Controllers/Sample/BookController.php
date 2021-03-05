<?php

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Book\BookCreateRequest;
use App\Http\Requests\Sample\Book\BookIndexRequest;
use App\Http\Requests\Sample\Book\BookShowRequest;
use App\Http\Requests\Sample\Book\BookUpdateRequest;
use App\Models\Sample\Book;
use Exception;
use Illuminate\Http\Response;

// FIXME: サンプルコードです。
class BookController extends Controller
{
    /**
     * @param BookIndexRequest $request
     * @return Response
     */
    public function index(BookIndexRequest $request): Response
    {
        $books = Book::whereUserId($request->user()->id)->get();
        return response($books);
    }

    /**
     * @param BookCreateRequest $request
     * @return Response
     */
    public function store(BookCreateRequest $request): Response
    {
        $book = Book::createWithUser($request->all(), $request->user());
        return response($book);
    }

    /**
     * @param BookShowRequest $request
     * @param Book $book
     * @return Response
     */
    public function show(BookShowRequest $request, Book $book): Response
    {
        return response($book);
    }

    /**
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
