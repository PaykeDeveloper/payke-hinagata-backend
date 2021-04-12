<?php

// FIXME: SAMPLE CODE

namespace App\Policies;

use Illuminate\Http\Response;
use App\Models\Sample\Company;
use App\Models\Sample\Staff;
use App\Models\User;
use App\Policies\Common\AuthorizablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = Staff::class;
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
        // Staff のパーミッションチェック (Company に属する)
        foreach ($user->companies() as $company) {
            foreach ($company->staff as $staff) {
                if ($staff->user_id === $user->id) {
                    if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                        return true;
                    }
                }
            }
        }

        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff  $staff
     * @return mixed
     */
    public function view(User $user, Staff $staff)
    {
        // Staff のパーミッションチェック (Company に属する)
        foreach ($user->companies() as $company) {
            foreach ($company->staff as $staff) {
                if ($staff->user_id === $user->id) {
                    if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }

        return abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Staff のパーミッションチェック (Company に属する)
        foreach ($user->companies() as $company) {
            foreach ($company->staff as $staff) {
                if ($staff->user_id === $user->id) {
                    if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }

        return abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff $staff
     * @return mixed
     */
    public function update(User $user, Staff $staff)
    {
        // Staff のパーミッションチェック (Company に属する)
        foreach ($user->companies() as $company) {
            foreach ($company->staff as $staff) {
                if ($staff->user_id === $user->id) {
                    if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }

        return abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff $staff
     * @return mixed
     */
    public function delete(User $user, Staff $staff)
    {
        // Staff のパーミッションチェック (Company に属する)
        foreach ($user->companies() as $company) {
            foreach ($company->staff as $staff) {
                if ($staff->user_id === $user->id) {
                    if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }

        return abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff $staff
     * @return mixed
     */
    public function restore(User $user, Staff $staff)
    {
        // Staff のパーミッションチェック (Company に属する)
        foreach ($user->companies() as $company) {
            foreach ($company->staff as $staff) {
                if ($staff->user_id === $user->id) {
                    if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }

        return abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff $staff
     * @return mixed
     */
    public function forceDelete(User $user, Staff $staff)
    {
        // Staff のパーミッションチェック (Company に属する)
        foreach ($user->companies() as $company) {
            foreach ($company->staff as $staff) {
                if ($staff->user_id === $user->id) {
                    if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                        return true;
                    }
                }
            }
        }

        // User パーミッションチェック (Admin)
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }

        return abort(Response::HTTP_NOT_FOUND);
    }
}
