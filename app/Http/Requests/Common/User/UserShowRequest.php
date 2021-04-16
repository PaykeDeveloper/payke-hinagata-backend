<?php

namespace App\Http\Requests\Common\User;

use App\Models\User;
use Illuminate\Http\Response;

class UserShowRequest extends UserIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        /** @var User $user */
        $user = $this->user();
        if ($user->hasAllViewPermissionTo(User::RESOURCE)) {
            return;
        }

        /** @var User $target_user */
        $target_user = $this->route('user');
        if ($target_user->id !== $user->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
