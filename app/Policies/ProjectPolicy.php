<?php

// FIXME: SAMPLE CODE

namespace App\Policies;

use Illuminate\Http\Response;
use App\Models\Sample\Division;
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
     * @param  \App\Models\Sample\Division  $division
     * @return mixed
     */
    public function viewAny(User $user, Division $division)
    {
        // MEMO: 親リソースの Division の権限チェックは middleware で判定済み (Controller のコンストラクタを参照)

        // FIXME: ProjectMember があればここで Member かどうかのチェックを行う

        // Member のパーミッションチェック
        foreach ($division->findMembersByUser($user) as $member) {
            if ($member->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                return true;
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sample\Project  $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        // MEMO: 親リソースの Division の権限チェックは middleware で判定済み (Controller のコンストラクタを参照)

        // FIXME: ProjectMember があればここで Member かどうかのチェックを行う

        // Member のパーミッションチェック
        foreach ($project->findMembersByUser($user) as $member) {
            if ($member->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model))) {
                return true;
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
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Division $division)
    {
        // MEMO: 親リソースの Division の権限チェックは middleware で判定済み (Controller のコンストラクタを参照)

        // FIXME: ProjectMember があればここで Member かどうかのチェックを行う

        // Member のパーミッションチェック
        foreach ($division->findMembersByUser($user) as $member) {
            $viewPermission = $member->hasAllOrPermissionTo('view', $this->baseName($this->model));
            $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

            if ($viewPermission && $funcPermission) {
                return true;
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sample\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        // MEMO: 親リソースの Division の権限チェックは middleware で判定済み (Controller のコンストラクタを参照)

        // FIXME: ProjectMember があればここで Member かどうかのチェックを行う

        // Member のパーミッションチェック
        foreach ($project->findMembersByUser($user) as $member) {
            $viewPermission = $member->hasAllOrPermissionTo('view', $this->baseName($this->model));
            $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

            if ($viewPermission && $funcPermission) {
                return true;
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sample\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        // MEMO: 親リソースの Division の権限チェックは middleware で判定済み (Controller のコンストラクタを参照)

        // FIXME: ProjectMember があればここで Member かどうかのチェックを行う

        // Member のパーミッションチェック
        foreach ($project->findMembersByUser($user) as $member) {
            $viewPermission = $member->hasAllOrPermissionTo('view', $this->baseName($this->model));
            $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

            if ($viewPermission && $funcPermission) {
                return true;
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sample\Project  $project
     * @return mixed
     */
    public function restore(User $user, Project $project)
    {
        // MEMO: 親リソースの Division の権限チェックは middleware で判定済み (Controller のコンストラクタを参照)

        // FIXME: ProjectMember があればここで Member かどうかのチェックを行う

        // Member のパーミッションチェック
        foreach ($project->findMembersByUser($user) as $member) {
            $viewPermission = $member->hasAllOrPermissionTo('view', $this->baseName($this->model));
            $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

            if ($viewPermission && $funcPermission) {
                return true;
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sample\Project  $project
     * @return mixed
     */
    public function forceDelete(User $user, Project $project)
    {
        // MEMO: 親リソースの Division の権限チェックは middleware で判定済み (Controller のコンストラクタを参照)

        // FIXME: ProjectMember があればここで Member かどうかのチェックを行う

        // Member のパーミッションチェック
        foreach ($project->findMembersByUser($user) as $member) {
            $viewPermission = $member->hasAllOrPermissionTo('view', $this->baseName($this->model));
            $funcPermission = $member->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($this->model));

            if ($viewPermission && $funcPermission) {
                return true;
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
