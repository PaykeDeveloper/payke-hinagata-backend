<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Policies\Common;

use App\Models\Common\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Permission::RESOURCE;

    public function viewAny(User $user): bool
    {
        return $user->hasViewAllPermissionTo(self::RESOURCE);
    }
}
