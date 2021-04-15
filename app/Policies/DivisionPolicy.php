<?php

// FIXME: SAMPLE CODE

namespace App\Policies;

use Illuminate\Http\Response;
use App\Models\Sample\Division;
use App\Models\User;
use App\Policies\Common\AuthorizablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class DivisionPolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = Division::class;
        parent::__construct();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // Member のパーミッションチェック (Division に属する)
        foreach ($user->divisions() as $division) {
            foreach ($division->members as $member) {
                if ($member->user_id === $user->id) {
                    if ($member->hasAllOrPermissionTo(__FUNCTION__, $this->modelName())) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName())) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Division  $division
     * @return mixed
     */
    public function view(User $user, Division $division)
    {
        // Member のパーミッションチェック
        foreach ($division->members as $member) {
            if ($member->user_id === $user->id) {
                if ($member->hasAllOrPermissionTo(__FUNCTION__, $this->modelName())) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
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
        // Member のパーミッションチェック (Division に属する)
        foreach ($user->divisions() as $division) {
            foreach ($division->members as $member) {
                if ($member->user_id === $user->id) {
                    $viewPermission = $member->hasAllOrPermissionTo('view', $this->modelName());
                    $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());

                    if ($viewPermission && $funcPermission) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Division  $division
     * @return mixed
     */
    public function update(User $user, Division $division)
    {
        // Member のパーミッションチェック
        foreach ($division->members as $member) {
            if ($member->user_id === $user->id) {
                $viewPermission = $member->hasAllOrPermissionTo('view', $this->modelName());
                $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Division  $division
     * @return mixed
     */
    public function delete(User $user, Division $division)
    {
        // Member のパーミッションチェック
        foreach ($division->members as $member) {
            if ($member->user_id === $user->id) {
                $viewPermission = $member->hasAllOrPermissionTo('view', $this->modelName());
                $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Division  $division
     * @return mixed
     */
    public function restore(User $user, Division $division)
    {
        // Member のパーミッションチェック
        foreach ($division->members as $member) {
            if ($member->user_id === $user->id) {
                $viewPermission = $member->hasAllOrPermissionTo('view', $this->modelName());
                $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Division  $division
     * @return mixed
     */
    public function forceDelete(User $user, Division $division)
    {
        // Member のパーミッションチェック
        foreach ($division->members as $member) {
            if ($member->user_id === $user->id) {
                $viewPermission = $member->hasAllOrPermissionTo('view', $this->modelName());
                $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->modelName());
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->modelName());
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
