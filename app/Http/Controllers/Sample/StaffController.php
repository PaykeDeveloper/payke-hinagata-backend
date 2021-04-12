<?php

// FIXME: SAMPLE CODE

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sample\Company\CompanyCreateRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Sample\Company\CompanyIndexRequest;
use App\Http\Requests\Sample\Company\CompanyShowRequest;
use App\Http\Requests\Sample\Company\CompanyUpdateRequest;
use App\Http\Requests\Sample\Staff\StaffCreateRequest;
use App\Models\Sample\Book;
use App\Models\Sample\Company;
use App\Models\Sample\Staff;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Response;
use Log;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Staff::class, 'staff');
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
        foreach ($company->staff as $staff) {
            // 取得を行うと自動的にレスポンスに挿入される
            $staff->getRoleNames();
            $staff->getDirectPermissions();
        }
        return response($company->staff);
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
    public function store(StaffCreateRequest $request, Company $company): Response
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        // Staff として追加
        $staff = Staff::createWithUserAndCompany($user, $company);

        // Role を追加
        $roles = $request->input('roles');
        if ($roles) {
            $staff->syncRoles($roles);
        }

        return response($staff);
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
    public function update(CompanyUpdateRequest $request, Company $company, Staff $staff): Response
    {
        // Role の更新
        $roles = $request->input('roles');
        if ($roles) {
            $staff->syncRoles($roles);
        }

        $staff->update($request->all());
        return response($staff);
    }

    /**
     * @param CompanyShowRequest $request
     * @param Company $company
     * @return Response
     * @throws Exception
     */
    public function destroy(CompanyShowRequest $request, Company $company, Staff $staff): Response
    {
        $staff->delete();
        return response(null, 204);
    }
}
