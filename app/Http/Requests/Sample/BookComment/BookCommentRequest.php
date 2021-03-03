<?php

namespace App\Http\Requests\Sample\BookComment;

use App\Http\Requests\FormRequest;
use Illuminate\Http\Response;

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
            //
        ];
    }

    protected function prepareForValidation()
    {
        $book = $this->route('book');
        $comment = $this->route('comment');
        if ($book->id !== $comment->book_id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
