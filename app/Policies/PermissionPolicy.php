<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

class PermissionPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Permission::RESOURCE;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Permission $permission): bool
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

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasUpdatePermissionTo(self::RESOURCE);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasDeletePermissionTo(self::RESOURCE);
    }

    public function restore(User $user, Permission $permission): bool
    {
        return false;
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return false;
    }
}
