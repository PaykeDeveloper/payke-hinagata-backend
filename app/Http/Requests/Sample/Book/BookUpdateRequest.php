<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Book;

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
            'author' => ['nullable', 'string', 'max:225'],
            'release_date' => ['nullable', 'date'],
        ];
    }
}
