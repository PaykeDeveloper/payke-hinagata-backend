<?php

// FIXME: SAMPLE CODE

namespace App\Models\Division;

use App\Models\Common\Permission;
use App\Models\Traits\HasAuthorization;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperMember
 */
class Member extends Model
{
    use HasFactory, HasAuthorization;

    public const RESOURCE = 'member';

    protected string $guard_name = 'web';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'permissions',
        'roles',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public static function findByUniqueKeys(int $userId, int $divisionId): ?self
    {
        return self::where('user_id', $userId)->where('division_id', $divisionId)->first();
    }

    public static function findFromRequest(User $user, Division $division): Collection
    {
        $member = self::findByUniqueKeys($user->id, $division->id);
        $enableAll = $member?->hasViewAllPermissionTo(self::RESOURCE)
            || $user->hasViewAllPermissionTo(self::RESOURCE);

        $members = $division->members;
        if ($enableAll) {
            return $members;
        } else {
            return $members->where('user_id', $user->id);
        }
    }

    public static function createFromRequest(mixed $attributes, Division $division): self
    {
        $member = new self();
        $member->fill($attributes);
        $member->division_id = $division->id;
        $member->save();
        if (array_key_exists('role_names', $attributes)) {
            $member->syncRoles($attributes['role_names']);
        }
        return $member->fresh();
    }

    public function updateFromRequest(mixed $attributes): self
    {
        $this->update($attributes);
        if (array_key_exists('role_names', $attributes)) {
            $this->syncRoles($attributes['role_names']);
        }

        return $this->fresh();
    }
}
