<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// FIXME: サンプルコードです。
class BookCommentRequest extends FormRequest
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
            'book_id' => ['required', 'exists:books,id']
        ];
    }

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null): array
    {
        $request = parent::all($keys);
        $request['book_id'] = $this->route('book');
        return $request;
    }
}
