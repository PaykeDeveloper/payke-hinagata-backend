<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use Exception;
use Illuminate\Http\Response;

// FIXME: サンプルコードです。
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Book::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookCreateRequest $request
     * @return Response
     */
    public function store(BookCreateRequest $request): Response
    {
        $book = Book::create($request->all());

        return response($book);
    }

    /**
     * Display the specified resource.
     *
     * @param Book $book
     * @return Response
     */
    public function show(Book $book): Response
    {
        return response($book);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     *
     * @param Book $book
     * @return Response
     * @throws Exception
     */
    public function destroy(Book $book): Response
    {
        $book->delete();

        return response(null, 204);
    }
}
