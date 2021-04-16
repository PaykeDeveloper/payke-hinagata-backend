<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Permission::class, Permission::RESOURCE);
    }

    public function index(Request $request): Response
    {
        return response(Permission::all());
    }
}
