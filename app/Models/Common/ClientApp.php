<?php

namespace App\Models\Common;

final class ClientApp
{
    public const NATIVE_ANDROID = 'com.example.native_app';
    public const NATIVE_IOS = 'com.example.nativeApp';
    public const WEB = 'web';

    public static function all(): array
    {
        return [self::NATIVE_ANDROID, self::NATIVE_IOS, self::WEB];
    }
}
