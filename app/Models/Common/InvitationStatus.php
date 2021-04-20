<?php

namespace App\Models\Common;

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
