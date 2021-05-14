<?php

// FIXME: SAMPLE CODE

namespace App\Models\Division;

use App\Models\Common\Permission;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

/**
 * @mixin IdeHelperDivision
 * @property ?int request_member_id
 * @property ?string[] permission_names
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
        $query = self::leftJoin('members', function (JoinClause $join) use ($user) {
            $join->on('divisions.id', '=', 'members.division_id');
            $join->where('members.user_id', '=', $user->id);
        })->select('divisions.*', 'members.id as request_member_id');
        if (!$user->hasAllViewPermissionTo(self::RESOURCE)) {
            $query->where('members.user_id', '=', $user->id);
        }
        return $query->get();
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

    public function setRequest(User $user): self
    {
        $member = Member::findByUniqueKeys($user->id, $this->id);
        $permissions = $member?->getAllPermissions()->all() ?? [];

        $this->request_member_id = $member?->id;
        $this->permission_names = array_map(function (Permission $permission) {
            return $permission->name;
        }, $permissions);
        return $this;
    }
}
