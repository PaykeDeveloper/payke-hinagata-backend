<?php

namespace App\Policies\Common;

use App\Models\Common\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

class PermissionPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Permission::RESOURCE;

    public function viewAny(User $user): bool
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Permission $permission): bool
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAllCreatePermissionTo(self::RESOURCE);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasAllUpdatePermissionTo(self::RESOURCE);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasAllDeletePermissionTo(self::RESOURCE);
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
