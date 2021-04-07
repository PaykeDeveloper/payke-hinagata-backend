<?php

namespace App\Models;

use App\Models\Common\AuthorizableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staff extends AuthorizableModel
{
    use HasFactory;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
