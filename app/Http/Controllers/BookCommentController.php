<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCommentCreateRequest;
use App\Http\Requests\BookCommentRequest;
use App\Http\Requests\BookCommentUpdateRequest;
use App\Models\Book;
use App\Models\BookComment;
use Exception;
use Illuminate\Http\Response;

// FIXME: サンプルコードです。
class BookCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Book $book
     * @return Response
     */
    public function index(Book $book): Response
    {
        $comments = BookComment::whereBookId($book->id)->get();

        return response($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookCommentCreateRequest $request
     * @param Book $book
     * @return Response
     */
    public function store(BookCommentCreateRequest $request, Book $book): Response
    {
        $comment = new BookComment();
        $comment->fill(($request->all()));
        $comment->book_id = $book->id;
        $comment->save();

        return response($comment);
    }

    /**
     * routes/api.phpに合わせて、引数の名前と順番を設定しましょう。
     * 間違えた名前を設定すると、値がNULLになります。。
     *
     * @param BookCommentRequest $request
     * @param Book $book
     * @param BookComment $comment
     * @return Response
     */
    public function show(BookCommentRequest $request, Book $book, BookComment $comment): Response
    {
        return response($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookCommentUpdateRequest $request
     * @param Book $book
     * @param BookComment $comment
     * @return Response
     */
    public function update(BookCommentUpdateRequest $request, Book $book, BookComment $comment): Response
    {
        $comment->update($request->all());

        return response($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BookCommentRequest $request
     * @param Book $book
     * @param BookComment $comment
     * @return Response
     * @throws Exception
     */
    public function destroy(BookCommentRequest $request, Book $book, BookComment $comment): Response
    {
        $comment->delete();

        return response(null, 204);
    }
}
