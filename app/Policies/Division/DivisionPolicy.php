<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Division;

use App\Models\Division\Division;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DivisionPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::division;

    public function viewAny(User $user): bool
    {
        return $user->hasViewPermissionTo(self::MODEL);
    }

    public function view(User $user, Division $division): bool
    {
        $member = $user->findMember($division);
        if ($member) {
            return $member->hasViewPermissionTo(self::MODEL);
        }
        return $user->hasViewAllPermissionTo(self::MODEL);
    }

    public function create(User $user): bool
    {
        return $user->hasCreatePermissionTo(self::MODEL);
    }

    public function update(User $user, Division $division): bool
    {
        $member = $user->findMember($division);
        if ($member) {
            return $member->hasUpdatePermissionTo(self::MODEL);
        }
        return $user->hasUpdateAllPermissionTo(self::MODEL);
    }

    public function delete(User $user, Division $division): bool
    {
        $member = $user->findMember($division);
        if ($member) {
            return $member->hasDeletePermissionTo(self::MODEL);
        }
        return $user->hasDeleteAllPermissionTo(self::MODEL);
    }
}
