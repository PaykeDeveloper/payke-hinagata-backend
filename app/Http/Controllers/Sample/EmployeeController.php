<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Company\CompanyIndexRequest;
use App\Http\Requests\Sample\Company\CompanyShowRequest;
use App\Http\Requests\Sample\Company\CompanyUpdateRequest;
use App\Http\Requests\Sample\Employee\EmployeeCreateRequest;
use App\Models\Sample\Book;
use App\Models\Sample\Company;
use App\Models\Sample\Employee;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Employee::class, 'employee');
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
    public function index(CompanyIndexRequest $request, Company $company): Response
    {
        foreach ($company->employees as $employee) {
            // 取得を行うと自動的にレスポンスに挿入される
            $employee->getRoleNames();
            $employee->getDirectPermissions();
        }
        return response($company->employees);
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
     * @param EmployeeCreateRequest $request
     * @return Response
     */
    public function store(EmployeeCreateRequest $request, Company $company): Response
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        // Employee として追加
        $employee = Employee::createWithUserAndCompany($user, $company);

        // Role を追加
        $roles = $request->input('roles');
        if ($roles) {
            $employee->syncRoles($roles);
        }

        return response($employee);
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
    public function update(CompanyUpdateRequest $request, Company $company, Employee $employee): Response
    {
        // Role の更新
        $roles = $request->input('roles');
        if ($roles) {
            $employee->syncRoles($roles);
        }

        $employee->update($request->all());
        return response($employee);
    }

    /**
     * @param CompanyShowRequest $request
     * @param Company $company
     * @return Response
     * @throws Exception
     */
    public function destroy(CompanyShowRequest $request, Company $company, Employee $employee): Response
    {
        $employee->delete();
        return response(null, 204);
    }
}
