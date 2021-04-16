<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use App\Policies\Common\AuthorizablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

class PermissionPolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewPermissionTo(Permission::RESOURCE)) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
        return false;
    }
}
