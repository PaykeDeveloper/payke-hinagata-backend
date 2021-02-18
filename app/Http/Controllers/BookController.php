<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
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
     * @param BookRequest $request
     * @return Response
     */
    public function store(BookRequest $request): Response
    {
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->release_date = $request->release_date;
        $book->save();

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
     * @param BookRequest $request
     * @param Book $book
     * @return Response
     */
    public function update(BookRequest $request, Book $book): Response
    {
        $book->title = $request->title;
        $book->author = $request->author;
        $book->release_date = $request->release_date;
        $book->save();

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
