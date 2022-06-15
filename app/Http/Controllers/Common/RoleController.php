<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Common\RoleResource;
use App\Models\Common\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Common Role
 */
class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "name": "Administrator",
     * "type": "user",
     * "required": true,
     * }
     * ]
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $resources = Role::all();
        return RoleResource::collection($resources);
    }
}
