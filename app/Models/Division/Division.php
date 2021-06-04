<?php

// FIXME: SAMPLE CODE

namespace App\Models\Division;

use App\Models\Common\Permission;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
        $divisions = $user->hasAllViewPermissionTo(self::RESOURCE)
            ? self::all()
            : self::whereHas('members', function (Builder $query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })->get();
        foreach ($divisions as $division) {
            $division->setRequest($user);
        }
        return $divisions;
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

        return $division->fresh();
    }

    public function updateFromRequest(mixed $attributes): self
    {
        $this->update($attributes);
        return $this->fresh();
    }

    public function setRequest(User $user): self
    {
        $member = Member::findByUniqueKeys($user->id, $this->id);
        $permissions = $member?->getAllPermissions()->all() ?? [];

        $this->attributes['request_member_id'] = $member?->id;
        $this->attributes['permission_names'] = array_map(function (Permission $permission) {
            return $permission->name;
        }, $permissions);
        return $this;
    }
}
