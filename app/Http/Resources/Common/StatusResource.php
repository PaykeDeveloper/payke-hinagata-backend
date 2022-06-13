<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'is_authenticated' => $this->resource != null,
        ];
    }
}
