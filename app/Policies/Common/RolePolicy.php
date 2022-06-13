<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Policies\Common;

use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::role;

    public function viewAny(User $user): bool
    {
        return $user->hasViewAllPermissionTo(self::MODEL);
    }
}
