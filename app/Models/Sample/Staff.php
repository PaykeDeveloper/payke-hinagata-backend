<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Common\AuthorizableModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staff extends AuthorizableModel
{
    use HasFactory;

    protected $guarded = [
        'id',
        'updated_at',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function createWithUserAndCompany(User $user, Company $company, array $attributes = [])
    {
        $staff = new Staff();
        $staff->fill($attributes);
        $staff->user_id = $user->id;
        $staff->company_id = $company->id;
        $staff->save();
        return $staff;
    }
}
