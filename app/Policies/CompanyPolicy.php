<?php

// FIXME: SAMPLE CODE

namespace App\Policies;

use Illuminate\Http\Response;
use App\Models\Sample\Company;
use App\Models\User;
use App\Policies\Common\AuthorizablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = Company::class;
        parent::__construct();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Company $company)
    {
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
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function view(User $user, Company $company)
    {
        // Staff のパーミッションチェック
        foreach ($company->staff as $staff) {
            if ($staff->user_id === $user->id) {
                if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                    return true;
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
        // Staff のパーミッションチェック
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
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function update(User $user, Company $company)
    {
        // Staff のパーミッションチェック
        foreach ($company->staff as $staff) {
            if ($staff->user_id === $user->id) {
                if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                    return true;
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
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function delete(User $user, Company $company)
    {
        // Staff のパーミッションチェック
        foreach ($company->staff as $staff) {
            if ($staff->user_id === $user->id) {
                if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                    return true;
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
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function restore(User $user, Company $company)
    {
        // Staff のパーミッションチェック
        foreach ($company->staff as $staff) {
            if ($staff->user_id === $user->id) {
                if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                    return true;
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
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function forceDelete(User $user, Company $company)
    {
        // Staff のパーミッションチェック
        foreach ($company->staff as $staff) {
            if ($staff->user_id === $user->id) {
                if ($staff->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                    return true;
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
