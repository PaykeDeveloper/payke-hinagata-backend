<?php

namespace App\Http\Resources\Division;

use App\Models\Division\Member;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Member $member */
        $member = $this->resource;
        return [
            'id' => $member->id,
            'division_id' => $member->division_id,
            'user_id' => $member->user_id,
            'permission_names' => $member->getAllPermissionNames(),
            'role_names' => $member->getRoleNames(),
            'created_at' => $member->created_at,
        ];
    }
}
