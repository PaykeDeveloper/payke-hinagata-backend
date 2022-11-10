<?php

namespace App\Models\Common;

final class UserRole
{
    public const ADMINISTRATOR = 'Administrator';

    public const ORGANIZER = 'Organizer';

    public const MANAGER = 'Manager';

    public const STAFF = 'Staff';

    public static function all(): array
    {
        return [
            self::ADMINISTRATOR,
            self::ORGANIZER,
            self::MANAGER,
            self::STAFF,
        ];
    }

    public static function required(): array
    {
        return [
            self::STAFF,
        ];
    }
}
