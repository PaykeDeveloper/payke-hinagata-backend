<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Project;

use App\Http\Requests\FormRequest;
use App\Models\Sample\Priority;
use Illuminate\Validation\Rules\Enum;

class ProjectIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'priority' => [new Enum(Priority::class)],
        ];
    }
}
