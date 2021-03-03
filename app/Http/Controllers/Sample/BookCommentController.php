<?php

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\BookComment\BookCommentCreateRequest;
use App\Http\Requests\Sample\BookComment\BookCommentRequest;
use App\Http\Requests\Sample\BookComment\BookCommentUpdateRequest;
use App\Jobs\Sample\CreateBookComment;
use App\Jobs\Sample\UpdateBookComment;
use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use Exception;
use Illuminate\Http\Response;

// FIXME: サンプルコードです。

/**
 * 〜 メソッドの引数について 〜
 * FormRequestを引数にするとメソッドに到達する前にバリデーションを掛けられます。
 * Modelを引数にするとURLパラメーターのキーで取得したデータが自動で入ります。（存在しない場合は404エラー）
 *
 * ただし、引数の名前と順番には十分注意をしましょう。
 * 引数の名前は、routes/api.phpと合わせる必要があります。
 * 間違えた名前を設定すると、値がNULLになり404エラーにはなりません。。
 *
 */
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
     * Display the specified resource.
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

    /**
     * カスタムメソッドの作成方法
     *
     * @param BookCommentCreateRequest $request
     * @param Book $book
     * @return Response
     */
    public function storeAsync(BookCommentCreateRequest $request, Book $book): Response
    {
        CreateBookComment::dispatch($book, $request->all());

        return response(null, 204);
    }

    /**
     * カスタムメソッドの作成方法
     *
     * @param BookCommentUpdateRequest $request
     * @param Book $book
     * @param BookComment $comment
     * @return Response
     */
    public function updateAsync(BookCommentUpdateRequest $request, Book $book, BookComment $comment): Response
    {
        UpdateBookComment::dispatch($comment, $request->all())->afterResponse();

        return response(null, 204);
    }
}