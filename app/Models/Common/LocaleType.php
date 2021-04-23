<?php

namespace App\Models\Common;

final class LocaleType
{
    public const EN = 'en';
    public const JA = 'ja';

    public static function all(): array
    {
        return [self::EN, self::JA];
    }

    public static function options(): array
    {
        return [
            [
                'value' => self::JA,
                'label' => '日本語',
            ],
        ];
    }
}
