<?php

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Models\Sample\CsvImport;
use App\Models\Sample\CsvImportType;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;

class BookUploadCsvController extends Controller
{
    /**
     * @response [
     * {
     * "id": 2,
     * "user_id": 1,
     * "original_file_name": "books.csv",
     * "status": 1,
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     * ]
     *
     * @param BookIndexRequest $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $csvList = CsvImport::whereUserId($request->user()->id)->whereCsvType(CsvImportType::BOOKS)->get();
        return response($csvList);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $book = BookUploadCsv::createWithUser($request->all(), $request->user());
        return response($book);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function show(Request $request, BookUploadCsv $book): Response
    {
        return response($book);
    }

    /**
     * @response {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     *
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function update(Request $request, BookUploadCsv $book): Response
    {
        $book->update($request->all());
        return response($book);
    }

    /**
     * @param Request $request
     * @param Book $book
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, BookUploadCsv $book): Response
    {
        $book->delete();
        return response(null, 204);
    }
}
