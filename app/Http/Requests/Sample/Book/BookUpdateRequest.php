<?php

namespace App\Http\Requests\Sample\Book;

// FIXME: サンプルコードです。
class BookUpdateRequest extends BookRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

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
