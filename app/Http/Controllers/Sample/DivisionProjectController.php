<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\DivisionProject\DivisionProjectCreateRequest;
use App\Http\Requests\Sample\DivisionProject\DivisionProjectIndexRequest;
use App\Http\Requests\Sample\DivisionProject\DivisionProjectShowRequest;
use App\Http\Requests\Sample\DivisionProject\DivisionProjectUpdateRequest;
use App\Jobs\Sample\CreateDivisionProject;
use App\Jobs\Sample\UpdateDivisionProject;
use App\Models\Sample\Book;
use App\Models\Sample\DivisionProject;
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
class DivisionProjectController extends Controller
{
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
     * @param DivisionProjectIndexRequest $request
     * @param Book $book
     * @return Response
     */
    public function index(DivisionProjectIndexRequest $request, Book $book): Response
    {
        $comments = DivisionProject::whereBookId($book->id)->get();
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
     * @param DivisionProjectCreateRequest $request
     * @param Book $book
     * @return Response
     */
    public function store(DivisionProjectCreateRequest $request, Book $book): Response
    {
        $comment = DivisionProject::createFromRequest($request->validated(), $book);
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
     * @param DivisionProjectShowRequest $request
     * @param Book $book
     * @param DivisionProject $comment
     * @return Response
     */
    public function show(DivisionProjectShowRequest $request, Book $book, DivisionProject $comment): Response
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
     * @param DivisionProjectUpdateRequest $request
     * @param Book $book
     * @param DivisionProject $comment
     * @return Response
     */
    public function update(DivisionProjectUpdateRequest $request, Book $book, DivisionProject $comment): Response
    {
        $comment->updateFromRequest($request->validated());
        return response($comment);
    }

    /**
     * @param DivisionProjectShowRequest $request
     * @param Book $book
     * @param DivisionProject $comment
     * @return Response
     * @throws Exception
     */
    public function destroy(DivisionProjectShowRequest $request, Book $book, DivisionProject $comment): Response
    {
        $comment->delete();
        return response(null, 204);
    }

    /**
     * @param DivisionProjectCreateRequest $request
     * @param Book $book
     * @return Response
     */
    public function storeAsync(DivisionProjectCreateRequest $request, Book $book): Response
    {
        CreateDivisionProject::dispatch($book, $request->validated());
        return response(null, 204);
    }

    /**
     * @param DivisionProjectUpdateRequest $request
     * @param Book $book
     * @param DivisionProject $comment
     * @return Response
     */
    public function updateAsync(DivisionProjectUpdateRequest $request, Book $book, DivisionProject $comment): Response
    {
        UpdateDivisionProject::dispatch($comment, $request->validated())->afterResponse();
        return response(null, 204);
    }
}
