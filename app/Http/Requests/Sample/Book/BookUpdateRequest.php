<?php

namespace App\Http\Requests\Sample\Book;

// FIXME: サンプルコードです。
class BookUpdateRequest extends BookShowRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'max:20'],
            'author' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
        ];
    }
}
