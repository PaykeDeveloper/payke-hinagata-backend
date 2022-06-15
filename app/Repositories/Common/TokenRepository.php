<?php

namespace App\Repositories\Common;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

class TokenRepository
{
    private const TOKEN_NAME = 'api_v1';

    public function store(array $attributes, User $user): NewAccessToken
    {
        $tokenKey = $this->getTokenKey($attributes);
        $user->tokens()->where('name', $tokenKey)->delete();
        return $user->createToken($tokenKey);
    }

    public function delete(User $user, string $token): void
    {
        $user->tokens()->where('token', $token)->delete();
    }

    private function getTokenKey(array $attributes): string
    {
        return implode('|', [
            self::TOKEN_NAME,
            $attributes['package_name'],
            $attributes['platform_type'],
            $attributes['device_id'] ?? '',
        ]);
    }
}
