<?php

namespace App\Http\Requests\Common\User;

use App\Http\Requests\FormRequest;
use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');
        return [
            'name' => ['string', 'max:255'],
            'email' => [
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user?->id),
            ],
            'role_names' => ['array'],
            'role_names.*' => ['string', Rule::exists('roles', 'name')->where(function (Builder $query) {
                return $query->whereIn('name', UserRole::all());
            })]
        ];
    }
}
