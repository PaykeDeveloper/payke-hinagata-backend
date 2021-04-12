<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Employee;

class EmployeeUpdateRequest extends EmployeeShowRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:20'],
            'roles' => ['array'],
        ];
    }
}
