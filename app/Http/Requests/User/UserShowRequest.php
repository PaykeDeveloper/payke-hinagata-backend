<?php

namespace App\Http\Requests\User;

use Illuminate\Http\Response;

class UserShowRequest extends UserIndexRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        // 全ての閲覧権限を持っている場合は権限判定をスキップ
        if ($this->user()->can('viewAll_user')) {
            return;
        }

        $user = $this->route('user');
        if ($user->id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
