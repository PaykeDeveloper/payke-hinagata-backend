<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Division\Division;

use App\Http\Requests\FormRequest;

class DivisionUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
        ];
    }
}
