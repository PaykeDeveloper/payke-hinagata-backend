<?php

namespace App\Policies;

use App\Models\Sample\Company;
use App\Models\Sample\Project;
use App\Models\User;
use App\Policies\Common\AuthorizablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = Project::class;
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
        // ProjectMember があればここで Member かどうかのチェック
        // サンプルでは ProjectMember は用意しないので全許可
        // Company の権限チェックは route 側で判定済み
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        // ProjectMember があればここで Member かどうかのチェック
        // サンプルでは ProjectMember は用意しないので全許可
        // Company の権限チェックは route 側で判定済み
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function restore(User $user, Project $project)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function forceDelete(User $user, Project $project)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }
}
