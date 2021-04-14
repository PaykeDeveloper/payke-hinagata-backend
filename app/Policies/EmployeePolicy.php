<?php

// FIXME: SAMPLE CODE

namespace App\Policies;

use App\Models\Sample\Company;
use Illuminate\Http\Response;
use App\Models\Sample\Employee;
use App\Models\User;
use App\Policies\Common\AuthorizablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = Employee::class;
        parent::__construct();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user, Company $company)
    {
        // Employee のパーミッションチェック (Company に属する)
        foreach ($company->employees as $employee) {
            if ($employee->user_id === $user->id) {
                if ($employee->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Employee $employee
     * @return mixed
     */
    public function view(User $user, Company $company, Employee $employee)
    {
        // Employee のパーミッションチェック (Company に属する)
        foreach ($company->employees as $employee) {
            if ($employee->user_id === $user->id) {
                if ($employee->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user, Company $company)
    {
        // Employee のパーミッションチェック (Company に属する)
        foreach ($company->employees as $employee) {
            if ($employee->user_id === $user->id) {
                $viewPermission = $employee->hasAllOrPermissionTo('view', $this->baseName($this->model));
                $funcPermission = $employee->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->baseName($this->model));
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Employee $employee
     * @return mixed
     */
    public function update(User $user, Company $company, Employee $employee)
    {
        // Employee のパーミッションチェック (Company に属する)
        foreach ($company->employees as $employee) {
            if ($employee->user_id === $user->id) {
                $viewPermission = $employee->hasAllOrPermissionTo('view', $this->baseName($this->model));
                $funcPermission = $employee->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->baseName($this->model));
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Employee $employee
     * @return mixed
     */
    public function delete(User $user, Company $company, Employee $employee)
    {
        // Employee のパーミッションチェック (Company に属する)
        foreach ($company->employees as $employee) {
            if ($employee->user_id === $user->id) {
                $viewPermission = $employee->hasAllOrPermissionTo('view', $this->baseName($this->model));
                $funcPermission = $employee->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->baseName($this->model));
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Employee $employee
     * @return mixed
     */
    public function restore(User $user, Company $company, Employee $employee)
    {
        // Employee のパーミッションチェック (Company に属する)
        foreach ($company->employees as $employee) {
            if ($employee->user_id === $user->id) {
                $viewPermission = $employee->hasAllOrPermissionTo('view', $this->baseName($this->model));
                $funcPermission = $employee->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->baseName($this->model));
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Employee $employee
     * @return mixed
     */
    public function forceDelete(User $user, Company $company, Employee $employee)
    {
        // Employee のパーミッションチェック (Company に属する)
        foreach ($company->employees as $employee) {
            if ($employee->user_id === $user->id) {
                $viewPermission = $employee->hasAllOrPermissionTo('view', $this->baseName($this->model));
                $funcPermission = $employee->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

                if ($viewPermission && $funcPermission) {
                    return true;
                }
            }
        }

        // User パーミッションチェック (Admin)
        $viewPermission = $user->hasAllOrPermissionTo('view', $this->baseName($this->model));
        $funcPermission = $user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));
        if ($viewPermission && $funcPermission) {
            return true;
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
