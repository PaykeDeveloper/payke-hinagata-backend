<?php

namespace App\Models;

use App\Models\Division\Member;
use App\Models\Sample\Book;
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
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['all_permissions'];

    public function getAllPermissionsAttribute(): \Illuminate\Support\Collection
    {
        return $this->getAllPermissions();
    }

    public function preferredLocale(): ?string
    {
        return $this->locale;
    }

    // FIXME: SAMPLE CODE
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * 全閲覧権限がある場合は全て、それ以外は Id で絞り込む
     */
    public static function getFromRequest(User $user): Collection
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            // 全ての閲覧権限を持っている場合は全データ取得
            return static::all();
        } else {
            return static::whereId($user->id)->get();
        }
    }

    public function updateFromRequest(mixed $attributes): self
    {
        $this->update($attributes);
        if (array_key_exists('roles', $attributes)) {
            $this->syncRoles($attributes['roles']);
        }
        return $this;
    }
}
