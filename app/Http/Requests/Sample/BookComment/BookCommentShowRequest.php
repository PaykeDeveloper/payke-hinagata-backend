<?php

namespace App\Http\Requests\Sample\BookComment;

use Illuminate\Http\Response;

// FIXME: サンプルコードです。
class BookCommentShowRequest extends BookCommentIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $book = $this->route('book');
        $comment = $this->route('comment');
        if ($book->id !== $comment->book_id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
