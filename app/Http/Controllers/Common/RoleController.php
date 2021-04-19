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
        $this->authorizeResource(Role::class);
    }

    /**
     * @response [
     * {
     * "id": 1,
     * "name": "Admin",
     * "type": "user"
     * }
     * ]
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return response(Role::all());
    }
}
