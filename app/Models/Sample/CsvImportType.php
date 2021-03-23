<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

final class CsvImportType
{
    public const BOOKS = 1;

    public static function all(): array
    {
        return [self::BOOKS];
    }
}
