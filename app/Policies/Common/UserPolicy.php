<?php

namespace App\Policies\Common;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

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

    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        if (
            $user->id === $targetUser->id &&
            $user->hasOwnViewPermissionTo(self::RESOURCE)
        ) {
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

    public function update(User $user, User $targetUser): bool
    {
        if (!$this->view($user, $targetUser)) {
            return false;
        }

        if (
            $user->id === $targetUser->id &&
            $user->hasOwnUpdatePermissionTo(self::RESOURCE)
        ) {
            return true;
        }

        if ($user->hasAllUpdatePermissionTo(self::RESOURCE)) {
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
            $user->hasOwnDeletePermissionTo(self::RESOURCE)
        ) {
            return true;
        }

        if ($user->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }
}
