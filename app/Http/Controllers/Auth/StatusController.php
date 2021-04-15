<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @response {
 * "is_authenticated": false
 * }
 *
 * @package App\Http\Controllers
 */
class StatusController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $is_authenticated = !!auth()->guard('sanctum')->user();
        $status = [
            'is_authenticated' => $is_authenticated,
        ];
        return response($status);
    }
}
