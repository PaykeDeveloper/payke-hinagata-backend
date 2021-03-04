<?php

namespace App\Http\Requests\Sample\Book;

use App\Http\Requests\FormRequest;
use Illuminate\Http\Response;

// FIXME: サンプルコードです。
class BookRequest extends FormRequest
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
            //
        ];
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $book = $this->route('book');
        if ($book->user->id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
