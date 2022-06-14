<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;

class TokenResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var NewAccessToken $accessToken */
        $accessToken = $this->resource;
        return [
            'token' => $accessToken->plainTextToken,
        ];
    }
}
