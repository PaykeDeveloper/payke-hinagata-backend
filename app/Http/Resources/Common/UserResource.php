<?php

namespace App\Http\Resources\Common;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'locale' => $this->locale,
            'permission_names' => $this->getAllPermissionNames(),
            'role_names' => $this->getRoleNames(),
            'created_at' => $this->created_at,
        ];
    }
}
