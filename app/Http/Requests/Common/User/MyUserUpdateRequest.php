<?php

namespace App\Http\Requests\Common\User;

use App\Http\Requests\FormRequest;

class MyUserUpdateRequest extends FormRequest
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
            'locale' => ['required', 'string'],
        ];
    }
}
