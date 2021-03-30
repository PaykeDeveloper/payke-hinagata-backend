<?php

namespace App\Models\Common;

final class PlatformType
{
    public const ANDROID = 'android';
    public const IOS = 'ios';
    public const WEB = 'foo';

    public static function all(): array
    {
        return [self::ANDROID, self::IOS, self::WEB];
    }
}
