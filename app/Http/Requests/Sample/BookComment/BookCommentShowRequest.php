<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\BookComment;

use Illuminate\Http\Response;

class BookCommentShowRequest extends BookCommentIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        // 全ての閲覧権限を持っている場合は権限判定をスキップ
        if ($this->user()->can('viewAll_bookComment')) {
            return;
        }

        $book = $this->route('book');
        $comment = $this->route('comment');
        if ($book->id !== $comment->book_id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
