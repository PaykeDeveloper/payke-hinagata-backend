<?php

namespace App\Models;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Traits\HasAuthorization;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasApiTokens, HasFactory, Notifiable, HasAuthorization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function preferredLocale(): ?string
    {
        return $this->locale;
    }

    public function getAllPermissionNames(): Collection
    {
        return $this->getAllPermissions()->pluck('name');
    }

    // FIXME: SAMPLE CODE
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function findMember(Division $division): ?Member
    {
        return $this->members->firstWhere('division_id', '=', $division->id);
    }
}
