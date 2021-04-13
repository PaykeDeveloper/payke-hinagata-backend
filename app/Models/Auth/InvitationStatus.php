<?php

namespace App\Models\Auth;

final class InvitationStatus
{
    public const PENDING = 0;
    public const APPROVED = 1;
    public const DENIED = 2;

    public static function all(): array
    {
        return [self::PENDING, self::APPROVED, self::DENIED];
    }
}
