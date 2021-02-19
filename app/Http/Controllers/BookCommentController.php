<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCommentCreateRequest;
use App\Http\Requests\BookCommentRequest;
use App\Http\Requests\BookCommentUpdateRequest;
use App\Models\BookComment;
use Exception;
use Illuminate\Http\Response;

// FIXME: サンプルコードです。
class BookCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param BookCommentRequest $request
     * @return Response
     */
    public function index(BookCommentRequest $request): Response
    {
        $comments = BookComment::whereBookId($request->book_id)->get();
        return response($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookCommentCreateRequest $request
     * @return Response
     */
    public function store(BookCommentCreateRequest $request): Response
    {
        $book = BookComment::create($request->all());

        return response($book);
    }

    /**
     * Display the specified resource.
     *
     * @param BookComment $bookComment
     * @param string $book_id
     * @return Response
     */
    public function show(BookComment $bookComment, string $book_id): Response
    {
        if ($bookComment->book_id !== $book_id) {
            abort(404);
        }

        return response($bookComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookCommentUpdateRequest $request
     * @param BookComment $bookComment
     * @return Response
     */
    public function update(BookCommentUpdateRequest $request, BookComment $bookComment): Response
    {
        if ($bookComment->book_id !== $request->route('book')) {
            abort(404);
        }

        $bookComment->update($request->all());

        return response($bookComment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BookComment $bookComment
     * @return Response
     * @throws Exception
     */
    public function destroy(BookComment $bookComment): Response
    {
        $bookComment->delete();

        return response(null, 204);
    }
}
