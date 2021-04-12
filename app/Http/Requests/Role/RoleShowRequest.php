<?php

namespace App\Http\Requests\Role;

use Illuminate\Http\Response;

class RoleShowRequest extends RoleIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();
    }
}
