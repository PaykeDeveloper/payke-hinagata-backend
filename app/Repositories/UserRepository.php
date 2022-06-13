<?php

namespace App\Repositories;

use App\Models\ModelType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function index(User $user): Collection
    {
        $query = match ($user->hasViewAllPermissionTo(ModelType::user)) {
            true => User::query(),
            false => User::whereId($user->id),
        };
        return $query->get();
    }

    public function update(array $attributes, User $user): User
    {
        $updateEmail = array_key_exists('email', $attributes) && $user->email !== $attributes['email'];
        if ($updateEmail) {
            $user->email_verified_at = null;
        }
        $user->update($attributes);
        if ($updateEmail) {
            $user->sendEmailVerificationNotification();
        }
        if (array_key_exists('role_names', $attributes)) {
            $user->syncRoles($attributes['role_names']);
        }
        return $user->fresh();
    }
}
