<?php

namespace App\Http\Requests\Role;

class RoleCreateRequest extends RoleIndexRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
        ];
    }
}
