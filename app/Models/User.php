<?php

namespace App\Models;

use App\Models\Sample\Book;
use App\Models\Sample\Member;
use App\Models\Traits\HasAllOrPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as BaseModel;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends BaseModel implements MustVerifyEmail, HasLocalePreference
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use HasAllOrPermissions;

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

    // FIXME: SAMPLE CODE
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function divisions(): array
    {
        // get all division ids belonging to the user
        $divisions = [];
        foreach ($this->members()->get() as $member) {
            $divisions[] = $member->division;
        }
        // remove duplicates
        $unique_divisions = [];
        foreach ($divisions as $division) {
            $unique_divisions[$division->id] = $division;
        }
        return array_values($unique_divisions);
    }

    /**
     * 全閲覧権限がある場合は全て、それ以外は Id で絞り込む
     */
    public static function allOrWhereId(User $user): Collection
    {
        if ($user->can('viewAnyAll_' . strtolower(basename(strtr(static::class, '\\', '/'))))) {
            // 全ての閲覧権限を持っている場合は全データ取得
            return static::all();
        } else {
            return static::whereId($user->id)->get();
        }
    }
}
