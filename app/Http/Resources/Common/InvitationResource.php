<?php

namespace App\Http\Resources\Common;

use App\Models\Common\Invitation;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Invitation $this */
        return [
            'id' => $this->id,
            'status' => $this->status,
            'name' => $this->name,
            'email' => $this->email,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
        ];
    }
}
