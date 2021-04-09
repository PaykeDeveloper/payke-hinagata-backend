<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Company\CompanyCreateRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Sample\Company\CompanyIndexRequest;
use App\Http\Requests\Sample\Company\CompanyShowRequest;
use App\Http\Requests\Sample\Company\CompanyUpdateRequest;
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
        // Company の Staff の user_id が一致するもののみ表示
        $companies = Company::whereHas('staff', function (Builder $query) use ($request) {
            $query->where('user_id', '=', $request->user()->id);
        })->get();

        return response($companies);
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
     * @param CompanyCreateRequest $request
     * @return Response
     */
    public function store(CompanyCreateRequest $request): Response
    {
        $company = Company::create($request->all());
        return response($company);
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
     * @param CompanyShowRequest $request
     * @param Book $book
     * @return Response
     */
    public function show(CompanyShowRequest $request, Company $company): Response
    {
        return response($company);
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
     * @param CompanyUpdateRequest $request
     * @param Company $company
     * @return Response
     */
    public function update(CompanyUpdateRequest $request, Company $company): Response
    {
        $company->update($request->all());
        return response($company);
    }

    /**
     * @param CompanyShowRequest $request
     * @param Company $company
     * @return Response
     * @throws Exception
     */
    public function destroy(CompanyShowRequest $request, Company $company): Response
    {
        $company->delete();
        return response(null, 204);
    }
}
