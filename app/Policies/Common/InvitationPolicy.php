<?php

namespace App\Policies\Common;

use App\Models\Common\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

class InvitationPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Invitation::RESOURCE;

    public function viewAny(User $user): bool
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Invitation $invitation): bool
    {
        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
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

    public function update(User $user, Invitation $invitation): bool
    {
        if (!$this->view($user, $invitation)) {
            return false;
        }

        if ($user->hasAllUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        if (!$this->view($user, $invitation)) {
            return false;
        }

        if ($user->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }
}
