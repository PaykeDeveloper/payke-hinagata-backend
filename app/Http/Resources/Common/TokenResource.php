<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;

class TokenResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var NewAccessToken $this */
        return [
            'token' => $this->plainTextToken,
        ];
    }
}
