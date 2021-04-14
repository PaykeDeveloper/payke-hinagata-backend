<?php

namespace App\Models;

use App\Models\Sample\Employee;
use App\Models\Traits\HasAllOrPermissions;
use App\Models\Auth\Invitation;
use App\Models\Sample\Book;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
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

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    // FIXME: SAMPLE CODE
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function divisions(): array
    {
        // get all division ids belonging to the user
        $divisions = [];
        foreach ($this->employees()->get() as $employee) {
            $divisions[] = $employee->division;
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
