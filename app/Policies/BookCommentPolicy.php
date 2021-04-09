<?php

// FIXME: SAMPLE CODE

namespace App\Policies;

use App\Policies\Common\AuthorizablePolicy;
use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookCommentPolicy extends AuthorizablePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = BookComment::class;
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
     * @param  \App\Models\BookComment  $bookComment
     * @return mixed
     */
    public function view(User $user, BookComment $bookComment)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
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
     * @param  \App\Models\BookComment  $bookComment
     * @return mixed
     */
    public function update(User $user, BookComment $bookComment)
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
     * @param  \App\Models\BookComment  $bookComment
     * @return mixed
     */
    public function delete(User $user, BookComment $bookComment)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasPermissionTo(__FUNCTION__ . '_' . $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BookComment  $bookComment
     * @return mixed
     */
    public function restore(User $user, BookComment $bookComment)
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
     * @param  \App\Models\BookComment  $bookComment
     * @return mixed
     */
    public function forceDelete(User $user, BookComment $bookComment)
    {
        foreach ($this->model::permissionModels() as $model) {
            if ($user->hasAllOrPermissionTo(__FUNCTION__, $this->baseName($model))) {
                return true;
            }
        }
        return false;
    }
}
