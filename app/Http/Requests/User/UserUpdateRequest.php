<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class UserUpdateRequest extends UserShowRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'roles' => ['nullable', 'array']
        ];
    }

    // Roles のバリデーション
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (ValidationValidator $validator) {
            $data = $validator->getData();

            $roles = $data['roles'] ?? null;

            if (!$roles) {
                return;
            }

            // Super Admin の追加は禁止
            if (in_array('Super Admin', $roles)) {
                $validator->errors()->add('roles', 'failed to update');
                return;
            }
        });
    }
}
