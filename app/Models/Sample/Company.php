<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Common\AuthorizableModel;
use App\Models\User;
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

    /**
     * 指定したユーザーの Staff を取得
     */
    public function findStaffByUser(User $user)
    {
        return $this->staff()->where('user_id', $user->id)->get();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
