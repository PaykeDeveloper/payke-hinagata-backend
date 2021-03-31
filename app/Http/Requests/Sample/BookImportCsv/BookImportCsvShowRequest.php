<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Sample\BookImportCsv;

use App\Models\Sample\CsvImport;
use Illuminate\Http\Response;

class BookImportCsvShowRequest extends BookImportCsvIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        $csvImport = CsvImport::find($this->route('book'));
        if ($csvImport->user_id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
