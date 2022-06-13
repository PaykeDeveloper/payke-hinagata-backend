<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Common\PermissionResource;
use App\Models\Common\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Common Permission
 */
class PermissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Permission::class);
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "name": "view_all__user"
     * }
     * ]
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $resources = Permission::all();
        return PermissionResource::collection($resources);
    }
}
