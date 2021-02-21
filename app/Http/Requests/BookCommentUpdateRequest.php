<?php

namespace App\Http\Requests;

use App\Models\FooBar;
use Illuminate\Validation\Rule;

// FIXME: サンプルコードです。
class BookCommentUpdateRequest extends BookCommentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'confirmed' => ['boolean'],
            'publish_date' => ['date'],
            'approved_at' => ['date', 'after:start_date'],
            'amount' => ['regex:/^\d+(\.\d{1,2})?$/'],
            'column' => ['numeric'],
            'choices' => [Rule::in(FooBar::all())],
            'description' => ['string'],
            'votes' => ['integer', 'min:1', 'max:5'],
        ];
    }
}
