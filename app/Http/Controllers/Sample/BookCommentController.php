<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\BookComment\BookCommentCreateRequest;
use App\Http\Requests\Sample\BookComment\BookCommentIndexRequest;
use App\Http\Requests\Sample\BookComment\BookCommentShowRequest;
use App\Http\Requests\Sample\BookComment\BookCommentUpdateRequest;
use App\Jobs\Sample\CreateBookComment;
use App\Jobs\Sample\UpdateBookComment;
use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use Exception;
use Illuminate\Http\Response;

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
    public function __construct()
    {
        $this->authorizeResource(BookComment::class, 'comment');
    }

    /**
     * @response [
     * {
     * "id": "4e69d027-833c-441b-b0e3-eb3805ff8064",
     * "book_id": 1,
     * "confirmed": true,
     * "publish_date": "2021-03-29",
     * "approved_at": "2021-03-05T11:06:00.000000Z",
     * "amount": 32,
     * "column": 3,
     * "choices": "foo",
     * "description": "ABC\nDEF",
     * "votes": 2,
     * "slug": "abc",
     * "created_at": "2021-03-05T08:43:33.000000Z",
     * "updated_at": "2021-03-05T11:07:25.000000Z",
     * "deleted_at": null,
     * "cover_url": "http://localhost:8000/storage/uploads/Ao32KH7ablb14RRUFZr2JR0P5rRlEYImx6FvV9Y2.png"
     * }
     * ]
     *
     * @param BookCommentIndexRequest $request
     * @param Book $book
     * @return Response
     */
    public function index(BookCommentIndexRequest $request, Book $book): Response
    {
        $comments = BookComment::whereBookId($book->id)->get();
        return response($comments);
    }

    /**
     * @response {
     * "id": "4e69d027-833c-441b-b0e3-eb3805ff8064",
     * "book_id": 1,
     * "confirmed": true,
     * "publish_date": "2021-03-29",
     * "approved_at": "2021-03-05T11:06:00.000000Z",
     * "amount": 32,
     * "column": 3,
     * "choices": "foo",
     * "description": "ABC\nDEF",
     * "votes": 2,
     * "slug": "abc",
     * "created_at": "2021-03-05T08:43:33.000000Z",
     * "updated_at": "2021-03-05T11:07:25.000000Z",
     * "deleted_at": null,
     * "cover_url": "http://localhost:8000/storage/uploads/Ao32KH7ablb14RRUFZr2JR0P5rRlEYImx6FvV9Y2.png"
     * }
     *
     * @param BookCommentCreateRequest $request
     * @param Book $book
     * @return Response
     */
    public function store(BookCommentCreateRequest $request, Book $book): Response
    {
        $comment = BookComment::createFromRequest($request->all(), $book);
        return response($comment);
    }

    /**
     * @response {
     * "id": "4e69d027-833c-441b-b0e3-eb3805ff8064",
     * "book_id": 1,
     * "confirmed": true,
     * "publish_date": "2021-03-29",
     * "approved_at": "2021-03-05T11:06:00.000000Z",
     * "amount": 32,
     * "column": 3,
     * "choices": "foo",
     * "description": "ABC\nDEF",
     * "votes": 2,
     * "slug": "abc",
     * "created_at": "2021-03-05T08:43:33.000000Z",
     * "updated_at": "2021-03-05T11:07:25.000000Z",
     * "deleted_at": null,
     * "cover_url": "http://localhost:8000/storage/uploads/Ao32KH7ablb14RRUFZr2JR0P5rRlEYImx6FvV9Y2.png"
     * }
     *
     * @param BookCommentShowRequest $request
     * @param Book $book
     * @param BookComment $comment
     * @return Response
     */
    public function show(BookCommentShowRequest $request, Book $book, BookComment $comment): Response
    {
        return response($comment);
    }

    /**
     * @response {
     * "id": "4e69d027-833c-441b-b0e3-eb3805ff8064",
     * "book_id": 1,
     * "confirmed": true,
     * "publish_date": "2021-03-29",
     * "approved_at": "2021-03-05T11:06:00.000000Z",
     * "amount": 32,
     * "column": 3,
     * "choices": "foo",
     * "description": "ABC\nDEF",
     * "votes": 2,
     * "slug": "abc",
     * "created_at": "2021-03-05T08:43:33.000000Z",
     * "updated_at": "2021-03-05T11:07:25.000000Z",
     * "deleted_at": null,
     * "cover_url": "http://localhost:8000/storage/uploads/Ao32KH7ablb14RRUFZr2JR0P5rRlEYImx6FvV9Y2.png"
     * }
     *
     * @param BookCommentUpdateRequest $request
     * @param Book $book
     * @param BookComment $comment
     * @return Response
     */
    public function update(BookCommentUpdateRequest $request, Book $book, BookComment $comment): Response
    {
        $comment->updateFromRequest($request->all());
        return response($comment);
    }

    /**
     * @param BookCommentShowRequest $request
     * @param Book $book
     * @param BookComment $comment
     * @return Response
     * @throws Exception
     */
    public function destroy(BookCommentShowRequest $request, Book $book, BookComment $comment): Response
    {
        $comment->delete();
        return response(null, 204);
    }

    /**
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
