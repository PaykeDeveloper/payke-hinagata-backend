<?php

namespace App\Http\Resources\Common;

use App\Models\Common\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Role $role */
        $role = $this->resource;
        return [
            'id' => $role->id,
            'name' => $role->name,
            'type' => $role->type,
            'required' => $role->required,
        ];
    }
}
