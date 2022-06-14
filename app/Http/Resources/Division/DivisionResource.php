<?php

namespace App\Http\Resources\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class DivisionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $request->user();
        /** @var Division $division */
        $division = $this->resource;
        /** @var ?Member $member */
        $member = $user->findMember($division);
        return [
            'id' => $division->id,
            'name' => $division->name,
            'request_member_id' => $member?->id,
            'permission_names' => $member?->getAllPermissionNames() ?? Collection::make(),
            'created_at' => $division->created_at,
        ];
    }

    public function toResponse($request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->members->load(['permissions', 'roles']);
        return parent::toResponse($request);
    }
}
