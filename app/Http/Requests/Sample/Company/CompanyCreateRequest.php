<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Company;

class CompanyCreateRequest extends CompanyIndexRequest
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
