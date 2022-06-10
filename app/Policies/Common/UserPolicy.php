<?php

namespace App\Policies\Common;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\HttpFoundation\Response;

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
    }

    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasViewAllPermissionTo(self::RESOURCE)) {
            return true;
        }
        if (
            $user->id === $targetUser->id &&
            $user->hasViewOwnPermissionTo(self::RESOURCE)
        ) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && $user->hasCreateAllPermissionTo(self::RESOURCE);
    }

    public function update(User $user, User $targetUser): bool
    {
        if (!$this->view($user, $targetUser)) {
            return false;
        }

        if (
            $user->id === $targetUser->id &&
            $user->hasUpdateOwnPermissionTo(self::RESOURCE)
        ) {
            return true;
        }

        if ($user->hasUpdateAllPermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, User $targetUser): bool
    {
        if (!$this->view($user, $targetUser)) {
            return false;
        }

        if (
            $user->id === $targetUser->id &&
            $user->hasDeleteOwnPermissionTo(self::RESOURCE)
        ) {
            return true;
        }

        if ($user->hasDeleteAllPermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }
}
