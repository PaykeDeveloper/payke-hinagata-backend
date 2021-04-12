<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\PermissionIndexRequest;
use App\Http\Requests\Permission\PermissionShowRequest;
use App\Models\Permission;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function __construct()
    {
    }

    public function index(PermissionIndexRequest $request): Response
    {
        return response(Permission::all());
    }

    public function show(PermissionShowRequest $request, Permission $permission): Response
    {
        return response($permission);
    }
}
