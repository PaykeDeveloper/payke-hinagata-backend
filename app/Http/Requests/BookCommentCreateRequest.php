<?php

namespace App\Http\Requests;

use App\Models\FooBar;
use Illuminate\Validation\Rule;

// FIXME: サンプルコードです。
class BookCommentCreateRequest extends BookCommentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
                'confirmed' => ['required', 'boolean'],
                'publish_date' => ['required', 'date'],
                'approved_at' => ['required', 'date', 'after:start_date'],
                'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
                'column' => ['required', 'numeric'],
                'choices' => ['required', Rule::in(FooBar::all())],
                'description' => ['required', 'string'],
                'votes' => ['required', 'integer', 'min:1', 'max:5'],
            ] + parent::rules();
    }
}
