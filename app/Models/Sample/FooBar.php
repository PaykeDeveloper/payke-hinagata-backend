<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

final class FooBar
{
    public const FOO = 'foo';
    public const BAR = 'bar';

    public static function all(): array
    {
        return [self::FOO, self::BAR];
    }
}
