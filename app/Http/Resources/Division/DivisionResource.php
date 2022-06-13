<?php

namespace App\Http\Resources\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class DivisionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $request->user();
        /** @var ?Member $member */
        $member = $user->findMember($this->resource);
        /** @var Division $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'request_member_id' => $member?->id,
            'permission_names' => $member?->getAllPermissions() ?? Collection::make(),
            'created_at' => $this->created_at,
        ];
    }
}
