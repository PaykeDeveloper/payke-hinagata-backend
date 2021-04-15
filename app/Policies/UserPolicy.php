<?php

// FIXME: SAMPLE CODE

namespace App\Policies;

use Illuminate\Http\Response;
use App\Policies\Common\AuthorizablePolicy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = User::class;
        parent::__construct();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName())) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return mixed
     */
    public function view(User $user, User $targetUser)
    {
        if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName())) {
            return true;
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return mixed
     */
    public function update(User $user, User $targetUser)
    {
        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return mixed
     */
    public function delete(User $user, User $targetUser)
    {
        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return mixed
     */
    public function restore(User $user, User $targetUser)
    {
        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return mixed
     */
    public function forceDelete(User $user, User $targetUser)
    {
        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        return false;
    }

    public function showMe(User $user): bool
    {
        return true;
    }
}
