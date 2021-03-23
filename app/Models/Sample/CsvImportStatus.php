<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

final class CsvImportStatus
{
    public const WAITING = 0;
    public const RUNNING = 1;
    public const SUCCESS = 2;
    public const FAILED = -1;

    public static function all(): array
    {
        return [self::FAILED, self::WAITING, self::RUNNING, self::SUCCESS];
    }
}
