<?php

namespace App\Policies\Common;

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
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Role $role): bool
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && $user->hasAllCreatePermissionTo(self::RESOURCE);
    }

    public function update(User $user, Role $role): bool
    {
        return $this->view($user, $role)
            && $user->hasAllUpdatePermissionTo(self::RESOURCE);
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->view($user, $role)
            && $user->hasAllDeletePermissionTo(self::RESOURCE);
    }
}
