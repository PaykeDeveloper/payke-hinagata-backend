<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\BookImportCsv;

class BookImportCsvCreateRequest extends BookImportCsvIndexRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'csv_file' => ['required', 'max:1024', 'file', 'mimes:csv,txt'],
        ];
    }
}
