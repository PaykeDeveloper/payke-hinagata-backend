<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Common Status
 * @response {
 * "is_authenticated": false
 * }
 */
class StatusController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $isAuthenticated = !!auth()->guard('sanctum')->user();
        $status = [
            'is_authenticated' => $isAuthenticated,
        ];
        return response($status);
    }
}
