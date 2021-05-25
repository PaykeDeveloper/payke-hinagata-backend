<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

final class Priority
{
    public const HIGH = 'high';
    public const MIDDLE = 'middle';
    public const LOW = 'low';

    public static function all(): array
    {
        return [self::HIGH, self::MIDDLE, self::LOW];
    }
}
