<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleIndexRequest;
use App\Http\Requests\Role\RoleShowRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use Illuminate\Http\Response;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Role::class, 'role');
    }

    /**
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

    /**
     * @param RoleCreateRequest $request
     * @return Response
     */
    public function store(RoleCreateRequest $request): Response
    {
        $role = Role::create(array_merge(['guard_name' => 'web'], $request->all()));
        return response($role);
    }

    /**
     * @param BookUpdateRequest $request
     * @param Role $role
     * @return Response
     */
    public function update(RoleUpdateRequest $request, Role $role): Response
    {
        $role->syncPermissions($request->input('permissions'));
        return response($role);
    }

    /**
     * @param RoleShowRequest $request
     * @param Role $role
     * @return Response
     * @throws Exception
     */
    public function destroy(RoleShowRequest $request, Role $role): Response
    {
        $role->delete();
        return response(null, 204);
    }
}
