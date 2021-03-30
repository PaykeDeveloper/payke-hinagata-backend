<?php

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Models\Sample\CsvImport;
use App\Models\Sample\CsvImportType;
use App\Http\Requests\Sample\BookUploadCsv\BookUploadCsvIndexRequest;
use App\Http\Requests\Sample\BookUploadCsv\BookUploadCsvCreateRequest;
use App\Http\Requests\Sample\BookUploadCsv\BookUploadCsvShowRequest;
use App\Imports\Sample\BookImport;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @group Csv Importer:Books
 */
class BookUploadCsvController extends Controller
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
     * @param BookUploadCsvIndexRequest $request
     * @return Response
     */
    public function index(BookUploadCsvIndexRequest $request): Response
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
     * @param BookUploadCsvCreateRequest $request
     * @return Response
     */
    public function store(BookUploadCsvCreateRequest $request): Response
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
     * @param BookUploadCsvShowRequest $request
     * @param CsvImport $csvImport
     * @return Response
     */
    public function show(BookUploadCsvShowRequest $request, CsvImport $csvImport): Response
    {
        return response($csvImport);
    }
}
