<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Traits\HasAllOrPermissions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    use HasFactory, HasRoles, HasAllOrPermissions;

    /** @var string */
    protected $guard_name = 'web';

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

    public static function createWithUserAndCompany(User $user, Company $company, array $attributes = []): Employee
    {
        $employee = new self();
        $employee->fill($attributes);
        $employee->user_id = $user->id;
        $employee->company_id = $company->id;
        $employee->save();
        return $employee;
    }
}
