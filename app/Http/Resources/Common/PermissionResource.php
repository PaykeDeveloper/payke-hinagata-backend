<?php

namespace App\Http\Resources\Common;

use App\Models\Common\Permission;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Permission $permission */
        $permission = $this->resource;
        return [
            'id' => $permission->id,
            'name' => $permission->name,
        ];
    }
}
