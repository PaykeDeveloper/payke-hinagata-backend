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
        if (!$this->view($user, $target_user)) {
            return false;
        }

        if ($user->hasAllUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasOwnUpdatePermissionTo(self::RESOURCE)) {
            return $user->id === $target_user->id;
        }

        return false;
    }

    public function delete(User $user, User $target_user): bool
    {
        if (!$this->view($user, $target_user)) {
            return false;
        }

        if ($user->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasOwnDeletePermissionTo(self::RESOURCE)) {
            return $user->id === $target_user->id;
        }

        return false;
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
