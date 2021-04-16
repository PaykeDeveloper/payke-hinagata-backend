<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Common;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;
use function abort;

class UserPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = User::RESOURCE;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, User $target_user): bool
    {
        if ($user->hasViewPermissionTo(self::RESOURCE)) {
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

    public function update(User $user, User $target_user): bool
    {
        return $this->view($user, $target_user)
            && $user->hasUpdatePermissionTo(self::RESOURCE);
    }

    public function delete(User $user, User $target_user): bool
    {
        return $this->view($user, $target_user)
            && $user->hasDeletePermissionTo(self::RESOURCE);
    }

    public function restore(User $user, User $target_user): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $target_user): bool
    {
        return false;
    }
}
