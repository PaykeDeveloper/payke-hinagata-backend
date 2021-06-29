<?php

/** @noinspection PhpUnusedParameterInspection */

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
        if ($user->hasViewAllPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Permission $permission): bool
    {
        if ($user->hasViewAllPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && $user->hasCreateAllPermissionTo(self::RESOURCE);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $this->view($user, $permission)
            && $user->hasUpdateAllPermissionTo(self::RESOURCE);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $this->view($user, $permission)
            && $user->hasDeleteAllPermissionTo(self::RESOURCE);
    }
}
