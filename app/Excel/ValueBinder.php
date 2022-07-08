<?php

namespace App\Excel;

use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class ValueBinder extends DefaultValueBinder
{
    public function bindValue(Cell $cell, $value): bool
    {
        if ($value instanceof \UnitEnum) {
            /** @phpstan-ignore-next-line */
            $value = $value->value;
        }

        return parent::bindValue($cell, $value);
    }
}
