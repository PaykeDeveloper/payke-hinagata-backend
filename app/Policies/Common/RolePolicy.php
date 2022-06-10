<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Policies\Common;

use App\Models\Common\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Role::RESOURCE;

    public function viewAny(User $user): bool
    {
        return $user->hasViewAllPermissionTo(self::RESOURCE);
    }
}
