<?php

namespace App\Models\Common;

final class UserRole
{
    public const ADMIN = 'Admin';
    public const MANAGER = 'Manager';

    public static function all(): array
    {
        return [
            self::ADMIN,
            self::MANAGER,
        ];
    }
}
