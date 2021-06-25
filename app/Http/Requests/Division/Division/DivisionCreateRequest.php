<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Division\Division;

use App\Http\Requests\FormRequest;

class DivisionCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
