<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleIndexRequest;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * ロールの一覧 (Super Admin のみ実行可能)
     *
     * @param RoleIndexRequest $request
     * @return Response
     */
    public function index(RoleIndexRequest $request): Response
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            // permissions の取得を行うと自動的にレスポンスに挿入される (permissions key)
            $role->getAllPermissions();
        }

        return response($roles);
    }
}
