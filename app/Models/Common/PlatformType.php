<?php

namespace App\Models\Common;

enum PlatformType: string
{
    case Android = 'android';
    case Ios = 'ios';
    case Web = 'web';
}
