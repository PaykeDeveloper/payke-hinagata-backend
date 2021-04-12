<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Project;

class ProjectUpdateRequest extends ProjectShowRequest
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
        ];
    }
}
