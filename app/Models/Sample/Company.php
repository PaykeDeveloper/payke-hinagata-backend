<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Traits\Authorizable;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Company extends Model
{
    use HasFactory, Authorizable;

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

    /**
     * 一覧取得
     * 権限によって取得される内容が自動的に可変する
     */
    public static function listByPermissions(User $user)
    {
        if ($user->hasPermissionTo('viewAnyAll_company')) {
            // ユーザー自体が全てを見れる権限があれば全てを返す
            return Company::all();
        } else {
            // Company の Staff の user_id が一致するもののみ表示
            return Company::whereHas('staff', function (Builder $query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })->get();
        }
    }
}
