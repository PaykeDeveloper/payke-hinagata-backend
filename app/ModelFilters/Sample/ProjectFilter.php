<?php

// FIXME: SAMPLE CODE

namespace App\ModelFilters\Sample;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends ModelFilter
{
    public function name(string $value): self|Builder
    {
        return $this->where('name', 'LIKE', "%$value%");
    }

    public function priority(string $value): self|Builder
    {
        return $this->where('priority', '=', $value);
    }
}
