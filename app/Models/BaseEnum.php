<?php

namespace App\Models;

/**
 * @property mixed $value
 */
interface BaseEnum
{
    public function getLabel(): array|string|null;
}
