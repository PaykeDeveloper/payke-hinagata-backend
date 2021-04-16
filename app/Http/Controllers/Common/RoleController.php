<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, Role::RESOURCE);
    }

    /**
     * ロールの一覧 (Super Admin のみ実行可能)
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return response(Role::all());
    }
}
