<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Project;

use App\Http\Requests\FormRequest;
use App\Models\Sample\Priority;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    protected array $casts = [
        'approved' => 'boolean',
        'description' => 'string',
    ];

    public function rules(): array
    {
        return [
//            'slug' => ['regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('projects')->ignore($this->id)],
            'name' => ['string', 'max:255'],
            'description' => ['string'],
            'priority' => ['nullable', Rule::in(Priority::all())],
            'approved' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after:start_date'],
            'difficulty' => ['nullable', 'integer', 'min:1', 'max:5'],
            'coefficient' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'productivity' => ['nullable', 'numeric', 'max:999999'],
            'cover' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/bmp', 'max:2048'],
            'lock_version' => ['integer'],
        ];
    }
}
