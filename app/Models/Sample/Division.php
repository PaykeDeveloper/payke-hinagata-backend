<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperDivision
 */
class Division extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * 指定したユーザーの Member を取得
     */
    public function findMembersByUser(User $user): Collection
    {
        return $this->members()->where('user_id', $user->id)->get();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * 一覧取得
     * 権限によって取得される内容が自動的に可変する
     */
    public static function listByPermissions(User $user): Collection
    {
        if ($user->hasPermissionTo('viewAnyAll_division')) {
            // ユーザー自体が全てを見れる権限があれば全てを返す
            return self::all();
        } else {
            // Division の Member の user_id が一致するもののみ表示
            return self::whereHas('members', function (Builder $query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })->get();
        }
    }
}
