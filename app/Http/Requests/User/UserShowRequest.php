<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\User;

use Illuminate\Http\Response;

class UserShowRequest extends UserIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        // 全ての閲覧権限を持っている場合は無条件でパス
        if ($this->user()->can('viewAnyAll_book')) {
            return;
        }

        $book = $this->route('book');
        if ($book->user->id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
