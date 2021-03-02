<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// FIXME: サンプルコードです。
class BookUpdateRequest extends FormRequest
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
//            'cover' => ['nullable', 'mimetypes:image/jpeg,image/png,image/bmp', 'max:1024'],
        ];
    }
}
