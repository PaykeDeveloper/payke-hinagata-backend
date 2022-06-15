<?php

namespace App\Policies\Common;

use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::permission;

    public function viewAny(User $user): bool
    {
        return $user->hasViewAllPermissionTo(self::MODEL);
    }
}
