<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * 指定したユーザーの Employee を取得
     */
    public function findEmployeesByUser(User $user): Collection
    {
        return $this->employees()->where('user_id', $user->id)->get();
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
        if ($user->hasPermissionTo('viewAnyAll_company')) {
            // ユーザー自体が全てを見れる権限があれば全てを返す
            return Company::all();
        } else {
            // Company の Employee の user_id が一致するもののみ表示
            return Company::whereHas('employees', function (Builder $query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })->get();
        }
    }
}
