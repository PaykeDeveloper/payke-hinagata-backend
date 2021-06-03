<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\Project;

use App\Http\Requests\FormRequest;
use App\Models\Sample\Priority;
use Illuminate\Validation\Rule;

class ProjectCreateRequest extends FormRequest
{
    protected array $casts = [
        'approved' => 'boolean',
        'description' => 'string',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
//            'slug' => ['required', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:projects'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'priority' => ['nullable', Rule::in(Priority::all())],
            'approved' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after:start_date'],
            'difficulty' => ['nullable', 'integer', 'min:1', 'max:5'],
            'coefficient' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'productivity' => ['nullable', 'numeric', 'max:999999'],
            'cover' => ['nullable', 'mimetypes:image/jpeg,image/png,image/bmp', 'max:2048'],
        ];
    }
}
