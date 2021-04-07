<?php

namespace App\Http\Requests\User;

use Gate;
use Illuminate\Http\Response;

class UserShowMeRequest extends UserIndexRequest
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
