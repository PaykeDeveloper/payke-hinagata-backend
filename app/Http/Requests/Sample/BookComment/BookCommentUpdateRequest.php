<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\BookComment;

use App\Models\Sample\FooBar;
use Illuminate\Validation\Rule;

class BookCommentUpdateRequest extends BookCommentShowRequest
{
    protected array $casts = [
        'confirmed' => 'boolean',
        'description' => 'string',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'confirmed' => ['nullable', 'boolean'],
            'publish_date' => ['nullable', 'date'],
            'approved_at' => ['nullable', 'date', 'after:start_date'],
            'amount' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'column' => ['nullable', 'numeric'],
            'choices' => ['nullable', Rule::in(FooBar::all())],
            'description' => ['string'],
            'votes' => ['nullable', 'integer', 'min:1', 'max:5'],
            'slug' => ['regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('book_comments')->ignore($this->id)],
            'cover' => ['nullable', 'mimetypes:image/jpeg,image/png,image/bmp', 'max:1024'],
        ];
    }
}
