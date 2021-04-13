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
        // Company (親リソース) の Policy 適用
        $this->middleware('can:view,company');

        // Policy は追加パラメータが必要なので authorizeResource ではなく手動で呼び出す
    }

    /**
     * @response [
     *   {
     *     "id": 5,
     *     "user_id": 2,
     *     "company_id": 18,
     *     "created_at": "2021-04-13T04:52:40.000000Z",
     *     "updated_at": "2021-04-13T04:52:40.000000Z",
     *     "permissions": [],
     *     "roles": [
     *       {
     *         "id": 9,
     *         "name": "Company Manager",
     *         "guard_name": "web",
     *         "created_at": "2021-04-13T02:55:12.000000Z",
     *         "updated_at": "2021-04-13T02:55:12.000000Z",
     *         "pivot": {
     *           "model_id": 5,
     *           "role_id": 9,
     *           "model_type": "App\\Models\\Sample\\Employee"
     *         }
     *       }
     *     ]
     *   }
     * ]
     *
     * @param CompanyIndexRequest $request
     * @return Response
     */
    public function index(CompanyIndexRequest $request, Company $company): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $company]);

        foreach ($company->employees as $employee) {
            // 取得を行うと自動的にレスポンスに挿入される
            $employee->getRoleNames();
            $employee->getDirectPermissions();
        }
        return response($company->employees);
    }

    /**
     * @response {
     *   "user_id": 3,
     *   "company_id": 18,
     *   "updated_at": "2021-04-13T06:04:44.000000Z",
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "id": 7,
     *   "roles": [
     *     {
     *       "id": 9,
     *       "name": "Company Manager",
     *       "guard_name": "web",
     *       "created_at": "2021-04-13T02:55:12.000000Z",
     *       "updated_at": "2021-04-13T02:55:12.000000Z",
     *       "pivot": {
     *         "model_id": 7,
     *         "role_id": 9,
     *         "model_type": "App\\Models\\Sample\\Employee"
     *       }
     *     }
     *   ]
     * }
     *
     * @param EmployeeCreateRequest $request
     * @return Response
     */
    public function store(EmployeeCreateRequest $request, Company $company): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $company]);

        $userId = $request->input('user_id');
        $user = User::find($userId);

        // Employee として追加
        $employee = Employee::createWithUserAndCompany($user, $company);

        // Role を追加
        $roles = $request->input('roles');
        if (!is_null($roles)) {
            $employee->syncRoles($roles);
        }

        // 取得を行うと自動的にレスポンスに挿入される
        $employee->getAllPermissions();

        return response($employee);
    }

    /**
     * @response {
     *   "id": 7,
     *   "user_id": 3,
     *   "company_id": 18,
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "updated_at": "2021-04-13T06:04:44.000000Z"
     * }
     *
     * @param CompanyShowRequest $request
     * @param Book $book
     * @return Response
     */
    public function show(CompanyShowRequest $request, Company $company, Employee $employee): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $company, $employee]);

        return response($employee);
    }

    /**
     * @response {
     *   "id": 7,
     *   "user_id": 3,
     *   "company_id": 18,
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "updated_at": "2021-04-13T06:04:44.000000Z",
     *   "roles": [],
     *   "permissions": []
     * }
     *
     * @param CompanyUpdateRequest $request
     * @param Company $company
     * @return Response
     */
    public function update(CompanyUpdateRequest $request, Company $company, Employee $employee): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $company, $employee]);

        // Role の更新
        $roles = $request->input('roles');
        if (!is_null($roles)) {
            $employee->syncRoles($roles);
        }

        // 取得を行うと自動的にレスポンスに挿入される
        $employee->getAllPermissions();

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
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $company, $employee]);

        // ロールの割り当て解除
        $employee->roles()->detach();

        $employee->delete();
        return response(null, 204);
    }
}
