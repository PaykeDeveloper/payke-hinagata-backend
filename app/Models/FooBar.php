<?php

namespace App\Models;

// FIXME: サンプルコードです。
final class FooBar
{
    public const FOO = 'foo';
    public const BAR = 'bar';

    public static function all(): array
    {
        return [self::FOO, self::BAR];
    }
}
