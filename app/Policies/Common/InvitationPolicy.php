<?php

namespace App\Policies\Common;

use App\Models\Common\Invitation;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::invitation;

    public function viewAny(User $user): bool
    {
        return $user->hasViewAllPermissionTo(self::MODEL);
    }

    public function view(User $user, Invitation $invitation): bool
    {
        return $user->hasViewAllPermissionTo(self::MODEL);
    }

    public function create(User $user): bool
    {
        return $user->hasCreateAllPermissionTo(self::MODEL);
    }

    public function update(User $user, Invitation $invitation): bool
    {
        return $user->hasUpdateAllPermissionTo(self::MODEL);
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        return $user->hasDeleteAllPermissionTo(self::MODEL);
    }
}
