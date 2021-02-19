<?php

namespace App\Http\Requests;

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
                //
            ] + parent::rules();
    }
}
