<?php

namespace App\Models;

use App\Models\Common\Permission;
use App\Models\Division\Member;
use App\Models\Traits\HasAuthorization;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasApiTokens, HasFactory, Notifiable, HasAuthorization;

    public const RESOURCE = 'user';

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
        'permissions',
        'roles',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['permission_names', 'role_names'];

    public function getPermissionNamesAttribute(): array
    {
        $permissions = $this->getAllPermissions()->all();
        return array_map(function (Permission $permission) {
            return $permission->name;
        }, $permissions);
    }

    public function getRoleNamesAttribute(): array
    {
        return $this->getRoleNames()->all();
    }

    public function preferredLocale(): ?string
    {
        return $this->locale;
    }

    // FIXME: SAMPLE CODE
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * 全閲覧権限がある場合は全て、それ以外は Id で絞り込む
     */
    public static function getFromRequest(User $user): Collection
    {
        $builder = static::with(['permissions', 'roles']);
        if (!$user->hasViewAllPermissionTo(self::RESOURCE)) {
            $builder = $builder->where('id', '=', $user->id);
        }
        return $builder->get();
    }

    public function updateFromRequest(mixed $attributes): self
    {
        $updateEmail = array_key_exists('email', $attributes) &&
            $this->email !== $attributes['email'];
        if ($updateEmail) {
            $this->email_verified_at = null;
        }
        $this->update($attributes);
        if ($updateEmail) {
            $this->sendEmailVerificationNotification();
        }
        if (array_key_exists('role_names', $attributes)) {
            $this->syncRoles($attributes['role_names']);
        }
        return $this->fresh();
    }
}
