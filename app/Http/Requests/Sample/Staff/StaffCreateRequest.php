<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Staff;

class StaffCreateRequest extends StaffIndexRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string'],
            'roles' => ['array'],
        ];
    }
}
