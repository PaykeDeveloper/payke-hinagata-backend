<?php

namespace App\Models\Common;

final class UserRole
{
    public const ADMINISTRATOR = 'Administrator';
    public const PERSONAL_DIRECTOR = 'Personnel Director';
    public const DIVISION_MANAGER = 'Division Manager';
    public const STAFF = 'Staff';

    public static function all(): array
    {
        return [
            self::ADMINISTRATOR,
            self::PERSONAL_DIRECTOR,
            self::DIVISION_MANAGER,
            self::STAFF,
        ];
    }
}
