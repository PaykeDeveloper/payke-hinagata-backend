<?php

namespace App\Http\Requests\Common\User;

use Illuminate\Validation\Rule;

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
            'name' => ['string', 'max:255'],
            'roles' => ['array'],
            'roles.*' => [Rule::exists('roles', 'name')]
        ];
    }
}
