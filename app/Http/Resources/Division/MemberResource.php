<?php

namespace App\Http\Resources\Division;

use App\Models\Division\Member;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Member $this */
        return [
            'id' => $this->id,
            'division_id' => $this->division_id,
            'user_id' => $this->user_id,
            'permission_names' => $this->getPermissionNames(),
            'role_names' => $this->getRoleNames(),
            'created_at' => $this->created_at,
        ];
    }
}
