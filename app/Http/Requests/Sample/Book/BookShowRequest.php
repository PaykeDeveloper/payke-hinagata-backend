<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Book;

use Illuminate\Http\Response;

class BookShowRequest extends BookIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $book = $this->route('book');
        if ($book->user->id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
