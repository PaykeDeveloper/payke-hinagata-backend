<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use ArchTech\Enums\Values;

enum Priority: string
{
    use Values;

    case High = 'high';
    case Middle = 'middle';
    case Low = 'low';
}
