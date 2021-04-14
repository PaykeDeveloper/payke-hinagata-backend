<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Company\CompanyCreateRequest;
use App\Http\Requests\Sample\Company\CompanyIndexRequest;
use App\Http\Requests\Sample\Company\CompanyShowRequest;
use App\Http\Requests\Sample\Company\CompanyUpdateRequest;
use App\Models\Sample\Book;
use App\Models\Sample\Company;
use Exception;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }

    /**
     * @response [
     *   {
     *     "id": 18,
     *     "name": "companpdafawefd)",
     *     "created_at": "2021-04-13T04:12:36.000000Z",
     *     "updated_at": "2021-04-13T09:33:04.000000Z"
     *   }
     * ]
     *
     * @param CompanyIndexRequest $request
     * @return Response
     */
    public function index(CompanyIndexRequest $request): Response
    {
        return response(Company::listByPermissions($request->user()));
    }

    /**
     * @response {
     *   "name": "brand new company",
     *   "updated_at": "2021-04-13T04:04:49.000000Z",
     *   "created_at": "2021-04-13T04:04:49.000000Z",
     *   "id": 16
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
     *   "id": 18,
     *   "name": "テストカンパニー",
     *   "created_at": "2021-04-13T04:12:36.000000Z",
     *   "updated_at": "2021-04-13T04:12:36.000000Z",
     *   "employees": [
     *     {
     *       "id": 5,
     *       "user_id": 2,
     *       "company_id": 18,
     *       "created_at": "2021-04-13T04:52:40.000000Z",
     *       "updated_at": "2021-04-13T04:52:40.000000Z",
     *       "permissions": [],
     *       "roles": [
     *         {
     *           "id": 9,
     *           "name": "Company Manager",
     *           "guard_name": "web",
     *           "created_at": "2021-04-13T02:55:12.000000Z",
     *           "updated_at": "2021-04-13T02:55:12.000000Z",
     *           "pivot": {
     *             "model_id": 5,
     *             "role_id": 9,
     *             "model_type": "App\\Models\\Sample\\Employee"
     *           }
     *         }
     *       ]
     *     }
     *   ]
     * }
     *
     * @param CompanyShowRequest $request
     * @param Company $company
     * @return Response
     */
    public function show(CompanyShowRequest $request, Company $company): Response
    {
        return response($company);
    }

    /**
     * @response {
     *   "id": 18,
     *   "name": "companpdafawefd)",
     *   "created_at": "2021-04-13T04:12:36.000000Z",
     *   "updated_at": "2021-04-13T09:33:04.000000Z",
     *   "employees": [
     *     {
     *       "id": 5,
     *       "user_id": 2,
     *       "company_id": 18,
     *       "created_at": "2021-04-13T04:52:40.000000Z",
     *       "updated_at": "2021-04-13T04:52:40.000000Z",
     *       "permissions": [],
     *       "roles": [
     *         {
     *           "id": 9,
     *           "name": "Company Manager",
     *           "guard_name": "web",
     *           "created_at": "2021-04-13T02:55:12.000000Z",
     *           "updated_at": "2021-04-13T02:55:12.000000Z",
     *           "pivot": {
     *             "model_id": 5,
     *             "role_id": 9,
     *             "model_type": "App\\Models\\Sample\\Employee"
     *           }
     *         }
     *       ]
     *     }
     *   ]
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
