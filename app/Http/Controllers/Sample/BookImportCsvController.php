<?php

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Models\Sample\CsvImport;
use App\Models\Sample\CsvImportType;
use App\Http\Requests\Sample\BookImportCsv\BookImportCsvIndexRequest;
use App\Http\Requests\Sample\BookImportCsv\BookImportCsvCreateRequest;
use App\Http\Requests\Sample\BookImportCsv\BookImportCsvShowRequest;
use App\Imports\Sample\BookImport;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @group Csv Importer:Books
 */
class BookImportCsvController extends Controller
{
    /**
     * @response [
     * {
     * "id": 2,
     * "user_id": 1,
     * "file_name_original": "books.csv",
     * "import_status": 1,
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     * ]
     *
     * @param BookImportCsvIndexRequest $request
     * @return Response
     */
    public function index(BookImportCsvIndexRequest $request): Response
    {
        $csvList = CsvImport::whereUserId($request->user()->id)->whereCsvType(CsvImportType::BOOKS)->get();
        return response($csvList);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "import_status": 0,
     * "file_name_original": "books.csv",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param BookImportCsvCreateRequest $request
     * @return Response
     */
    public function store(BookImportCsvCreateRequest $request): Response
    {
        $csvImport = CsvImport::createWithUser($request->file('csv_file'), $request->user());
        Excel::import(new BookImport($csvImport->id), $csvImport->getUplodedFileFullPath());
        return response($csvImport);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "import_status": 0,
     * "file_name_original": "books.csv",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param BookImportCsvShowRequest $request
     * @param CsvImport $csvImport
     * @return Response
     */
    public function show(BookImportCsvShowRequest $request, string $id): Response
    {
        $csvImport = CsvImport::find($id);
        return response($csvImport);
    }
}
