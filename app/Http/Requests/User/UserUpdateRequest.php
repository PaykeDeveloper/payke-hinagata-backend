<?php

namespace App\Http\Requests\User;

class UserUpdateRequest extends UserShowRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'roles' => ['nullable', 'array']
        ];
    }
}
