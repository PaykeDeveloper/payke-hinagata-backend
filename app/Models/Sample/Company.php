<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Common\AuthorizableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends AuthorizableModel
{
    use HasFactory;

    protected $guarded = [];

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
