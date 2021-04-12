<?php

namespace App\Http\Requests\Role;

class RoleUpdateRequest extends RoleShowRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array']
        ];
    }
}
