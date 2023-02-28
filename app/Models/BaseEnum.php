<?php

namespace App\Models;

interface BaseEnum
{
    public function getLabel(): array|string|null;
}
