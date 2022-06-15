<?php

namespace App\Http\Resources\Common;

use App\Models\Common\Invitation;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Invitation $invitation */
        $invitation = $this->resource;
        return [
            'id' => $invitation->id,
            'status' => $invitation->status,
            'name' => $invitation->name,
            'email' => $invitation->email,
            'role_names' => $invitation->role_names,
            'created_by' => $invitation->created_by,
            'created_at' => $invitation->created_at,
        ];
    }
}
