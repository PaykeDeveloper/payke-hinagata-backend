<?php

namespace App\Http\Resources\Division;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DivisionCollection extends ResourceCollection
{
    public function toResponse($request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->members->loadMissing(['permissions', 'roles.permissions']);
        return parent::toResponse($request);
    }
}
