<?php

// FIXME: SAMPLE CODE

namespace App\Models\Division;

final class MemberRole
{
    public const MANAGER = 'Division Manager';

    public const MEMBER = 'Member';

    public static function all(): array
    {
        return [
            self::MANAGER,
            self::MEMBER,
        ];
    }

    public static function required(): array
    {
        return [
            self::MEMBER,
        ];
    }
}
