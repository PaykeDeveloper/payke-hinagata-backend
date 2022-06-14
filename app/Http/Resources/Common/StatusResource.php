<?php

namespace App\Http\Resources\Common;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ?User $user */
        $user = $this->resource;
        return [
            'is_authenticated' => $user != null,
        ];
    }
}
