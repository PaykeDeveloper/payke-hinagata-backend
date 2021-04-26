<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\BookComment;

use Illuminate\Http\Response;

class DivisionProjectShowRequest extends BookCommentIndexRequest
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
