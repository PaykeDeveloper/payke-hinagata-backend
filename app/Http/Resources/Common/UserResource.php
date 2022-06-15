<?php

namespace App\Http\Resources\Common;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'locale' => $user->locale,
            'permission_names' => $user->getAllPermissionNames(),
            'role_names' => $user->getRoleNames(),
            'created_at' => $user->created_at,
        ];
    }
}
