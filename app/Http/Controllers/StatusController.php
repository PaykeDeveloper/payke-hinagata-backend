<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class StatusController
 * @package App\Http\Controllers
 * @unauthenticated
 */
class StatusController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $status = [
            'is_authenticated' => !!$request->user()
        ];
        return response($status);
    }
}
