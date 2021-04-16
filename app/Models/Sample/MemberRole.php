<?php

namespace App\Models\Sample;

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
}
