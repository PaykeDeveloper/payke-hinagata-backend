<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Common\StatusResource;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group Common Status
 *
 * @response {
 * "is_authenticated": false
 * }
 */
class StatusController extends Controller
{
    public function __invoke(Request $request): StatusResource
    {
        /** @var ?User $resource */
        $resource = auth()->guard('sanctum')->user();
        return StatusResource::make($resource);
    }
}
