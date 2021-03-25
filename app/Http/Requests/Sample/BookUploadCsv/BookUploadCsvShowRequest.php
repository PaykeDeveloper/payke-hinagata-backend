<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\BookUploadCsv;

use Illuminate\Http\Response;

class BookUploadCsvShowRequest extends BookUploadCsvIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $csvImport = $this->route('book');
        if ($csvImport->user_id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
