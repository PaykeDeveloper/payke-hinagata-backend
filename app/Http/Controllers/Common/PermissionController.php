<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Permission::class);
    }

    public function index(Request $request): Response
    {
        return response(Permission::all());
    }
}
