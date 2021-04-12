<?php

namespace App\Http\Requests\Role;

class RoleShowMeRequest extends RoleIndexRequest
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
}
