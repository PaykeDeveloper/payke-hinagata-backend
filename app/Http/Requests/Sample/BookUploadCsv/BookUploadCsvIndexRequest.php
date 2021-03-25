<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\BookUploadCsv;

use App\Http\Requests\FormRequest;

class BookUploadCsvIndexRequest extends FormRequest
{
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
        return [];
    }
}
