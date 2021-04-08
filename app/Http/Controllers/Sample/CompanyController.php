<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Sample\Book\BookCreateRequest;
use App\Http\Requests\Sample\Book\BookIndexRequest;
use App\Http\Requests\Sample\Book\BookShowRequest;
use App\Http\Requests\Sample\Book\BookUpdateRequest;
use App\Http\Requests\Sample\Company\CompanyIndexRequest;
use App\Http\Requests\Sample\Company\CompanyShowRequest;
use App\Models\Sample\Book;
use App\Models\Sample\Company;
use DB;
use Exception;
use Illuminate\Http\Response;
use Log;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }

    /**
     * @response [
     * {
     * "id": 2,
     * "user_id": 1,
     * "title": "Title 1",
     * "author": "Author 1",
     * "release_date": "2021-03-16",
     * "created_at": "2021-03-05T08:31:33.000000Z",
     * "updated_at": "2021-03-05T08:31:33.000000Z"
     * }
     * ]
     *
     * @param CompanyIndexRequest $request
     * @return Response
     */
    public function index(CompanyIndexRequest $request): Response
    {
        // $companies = Company::all();

        DB::enableQueryLog();

        // Company の Staff の user_id が一致するもののみ表示
        $companies = Company::whereHas('staff', function (Builder $query) use ($request) {
            $query->where('user_id', '=', $request->user()->id);
        })->get();

        Log::debug(DB::getQueryLog());

        return response($companies);
    }

    // /**
    //  * @response {
    //  * "id": 2,
    //  * "user_id": 1,
    //  * "title": "Title 1",
    //  * "author": "Author 1",
    //  * "release_date": "2021-03-16",
    //  * "created_at": "2021-03-05T08:31:33.000000Z",
    //  * "updated_at": "2021-03-05T08:31:33.000000Z"
    //  * }
    //  *
    //  * @param BookCreateRequest $request
    //  * @return Response
    //  */
    // public function store(BookCreateRequest $request): Response
    // {
    //     $book = Book::createWithUser($request->all(), $request->user());
    //     return response($book);
    // }

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
     * @param CompanyShowRequest $request
     * @param Book $book
     * @return Response
     */
    public function show(CompanyShowRequest $request, Company $company): Response
    {
        return response($company);
    }

    // /**
    //  * @response {
    //  * "id": 2,
    //  * "user_id": 1,
    //  * "title": "Title 1",
    //  * "author": "Author 1",
    //  * "release_date": "2021-03-16",
    //  * "created_at": "2021-03-05T08:31:33.000000Z",
    //  * "updated_at": "2021-03-05T08:31:33.000000Z"
    //  * }
    //  *
    //  * @param BookUpdateRequest $request
    //  * @param Book $book
    //  * @return Response
    //  */
    // public function update(BookUpdateRequest $request, Book $book): Response
    // {
    //     $book->update($request->all());
    //     return response($book);
    // }

    // /**
    //  * @param BookShowRequest $request
    //  * @param Book $book
    //  * @return Response
    //  * @throws Exception
    //  */
    // public function destroy(BookShowRequest $request, Book $book): Response
    // {
    //     $book->delete();
    //     return response(null, 204);
    // }
}
