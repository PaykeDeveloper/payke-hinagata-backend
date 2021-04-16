<?php

namespace App\Policies;

use App\Models\Common\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

class RolePolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Role::RESOURCE;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Role $role): bool
    {
        if ($user->hasViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasCreatePermissionTo(self::RESOURCE);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasUpdatePermissionTo(self::RESOURCE);
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasDeletePermissionTo(self::RESOURCE);
    }

    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
