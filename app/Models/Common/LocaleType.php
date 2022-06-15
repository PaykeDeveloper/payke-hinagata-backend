<?php

namespace App\Models\Common;

use App\Models\BaseEnum;

enum LocaleType: string implements BaseEnum
{
    case En = 'en';
    case Ja = 'ja';

    public function getLabel(): array|string|null
    {
        return match ($this) {
            self::En => __('English'),
            self::Ja => __('Japanese'),
        };
    }
}
