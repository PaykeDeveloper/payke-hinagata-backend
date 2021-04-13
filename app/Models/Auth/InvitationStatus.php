<?php

namespace App\Models\Auth;

final class InvitationStatus
{
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const DENIED = 'denied';

    public static function all(): array
    {
        return [self::PENDING, self::APPROVED, self::DENIED];
    }
}
