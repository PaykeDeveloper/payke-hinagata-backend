<?php

namespace App\Models\Common;

final class UserRole
{
    public const ADMIN = 'Admin';
    public const MANAGER = 'Manager';
    public const STAFF = 'Staff';

    public static function all(): array
    {
        return [
            self::ADMIN,
            self::MANAGER,
            self::STAFF,
        ];
    }
}
