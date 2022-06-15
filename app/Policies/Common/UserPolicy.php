<?php

namespace App\Policies\Common;

use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::user;

    public function viewAny(User $user): bool
    {
        return $user->hasViewPermissionTo(self::MODEL);
    }

    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }
        return $user->id === $targetUser->id &&
            $user->hasViewOwnPermissionTo(self::MODEL);
    }

    public function create(User $user): bool
    {
        return $user->hasCreateAllPermissionTo(self::MODEL);
    }

    public function update(User $user, User $targetUser): bool
    {
        if ($user->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }
        return $user->id === $targetUser->id &&
            $user->hasUpdateOwnPermissionTo(self::MODEL);
    }

    public function delete(User $user, User $targetUser): bool
    {
        if ($user->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }
        return $user->id === $targetUser->id &&
            $user->hasDeleteOwnPermissionTo(self::MODEL);
    }
}
