<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Division\DivisionIndexRequest;
use App\Http\Requests\Sample\Division\DivisionShowRequest;
use App\Http\Requests\Sample\Division\DivisionUpdateRequest;
use App\Http\Requests\Sample\Employee\EmployeeCreateRequest;
use App\Models\Sample\Division;
use App\Models\Sample\Employee;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function __construct()
    {
        // Division (親リソース) の Policy 適用
        $this->middleware('can:view,division');

        // Policy は追加パラメータが必要なので authorizeResource ではなく手動で呼び出す
    }

    /**
     * @response [
     *   {
     *     "id": 5,
     *     "user_id": 2,
     *     "division_id": 18,
     *     "created_at": "2021-04-13T04:52:40.000000Z",
     *     "updated_at": "2021-04-13T04:52:40.000000Z",
     *     "permissions": [],
     *     "roles": [
     *       {
     *         "id": 9,
     *         "name": "Division Manager",
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
     * @param DivisionIndexRequest $request
     * @return Response
     */
    public function index(DivisionIndexRequest $request, Division $division): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $division]);

        foreach ($division->employees as $employee) {
            // 取得を行うと自動的にレスポンスに挿入される
            $employee->getRoleNames();
            $employee->getDirectPermissions();
        }
        return response($division->employees);
    }

    /**
     * @response {
     *   "user_id": 3,
     *   "division_id": 18,
     *   "updated_at": "2021-04-13T06:04:44.000000Z",
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "id": 7,
     *   "roles": [
     *     {
     *       "id": 9,
     *       "name": "Division Manager",
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
    public function store(EmployeeCreateRequest $request, Division $division): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $division]);

        $userId = $request->input('user_id');

        /**
         * @var User|null
         */
        $user = User::find($userId);

        // Employee として追加
        $employee = Employee::createWithUserAndDivision($user, $division);

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
     *   "division_id": 18,
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "updated_at": "2021-04-13T06:04:44.000000Z"
     * }
     *
     * @param DivisionShowRequest $request
     * @param Division $division
     * @param Employee $employee
     * @return Response
     */
    public function show(DivisionShowRequest $request, Division $division, Employee $employee): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $division, $employee]);

        return response($employee);
    }

    /**
     * @response {
     *   "id": 7,
     *   "user_id": 3,
     *   "division_id": 18,
     *   "created_at": "2021-04-13T06:04:44.000000Z",
     *   "updated_at": "2021-04-13T06:04:44.000000Z",
     *   "roles": [],
     *   "permissions": []
     * }
     *
     * @param DivisionUpdateRequest $request
     * @param Division $division
     * @return Response
     */
    public function update(DivisionUpdateRequest $request, Division $division, Employee $employee): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $division, $employee]);

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
     * @param DivisionShowRequest $request
     * @param Division $division
     * @return Response
     * @throws Exception
     */
    public function destroy(DivisionShowRequest $request, Division $division, Employee $employee): Response
    {
        // Policy の呼び出し (追加パラメータを渡す為手動実行)
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Employee::class, $division, $employee]);

        // ロールの割り当て解除
        $employee->roles()->detach();

        $employee->delete();
        return response(null, 204);
    }
}
