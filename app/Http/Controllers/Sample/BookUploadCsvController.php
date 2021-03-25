<?php

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Book\BookShowRequest;
use App\Models\Sample\CsvImport;
use App\Models\Sample\CsvImportType;
use App\Http\Requests\Sample\BookUploadCsv\BookUploadCsvIndexRequest;
use App\Http\Requests\Sample\BookUploadCsv\BookUploadCsvCreateRequest;
use App\Http\Requests\Sample\BookUploadCsv\BookUploadCsvShowRequest;
use Illuminate\Http\Response;

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
     * "original_file_name": "books.csv",
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
        $book = CsvImport::createWithUser($request->file('csv_file'), $request->user());
        // TODO:バックグラウンドジョブの実行指定
        return response($book);
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
     * @param Book $book
     * @return Response
     */
    public function show(BookUploadCsvShowRequest $request, CsvImport $book): Response
    {
        return response($book);
    }
}
