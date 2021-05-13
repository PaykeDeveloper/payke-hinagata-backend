<?php

// FIXME: SAMPLE CODE

namespace App\Models\Division;

use App\Models\Common\Permission;
use App\Models\Sample\Project;
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

    public const RESOURCE = 'division';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * 一覧取得
     * 権限によって取得される内容が自動的に可変する
     */
    public static function getFromRequest(User $user): Collection
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            // ユーザー自体が全てを見れる権限があれば全てを返す
            return self::all();
        } else {
            // Division の Member の user_id が一致するもののみ表示
            return self::whereHas('members', function (Builder $query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })->get();
        }
    }

    public static function createFromRequest(mixed $attributes, User $user): self
    {
        $division = new self();
        $division->fill($attributes);
        $division->save();

        $member = Member::create([
            'user_id' => $user->id,
            'division_id' => $division->id,
        ]);
        $member->syncRoles(MemberRole::all());

        return $division;
    }

    private ?Member $member = null;

    public function setRequest(User $user): self
    {
        $this->member = Member::findByUniqueKeys($user->id, $this->id);
        $this->append('request_member_id');
        $this->append('permission_names');
        return $this;
    }

    public function getRequestMemberIdAttribute(): ?int
    {
        return $this->member?->id;
    }

    public function getPermissionNamesAttribute(): array
    {
        $permissions = $this->member?->getAllPermissions()->all() ?? [];
        return array_map(function (Permission $permission) {
            return $permission->name;
        }, $permissions);
    }
}
