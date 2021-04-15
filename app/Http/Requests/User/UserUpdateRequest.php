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

    /**
     * Roles の Super Admin 追加禁止のバリデーション
     *
     * Rule::notIn(['Super Admin']) は空配列を許容してくれないので手動で実装
     * カスタム rule を追加する手法もある
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (ValidationValidator $validator) {
            $data = $validator->getData();

            $roles = $data['roles'] ?? null;

            if (!$roles) {
                return;
            }

            if (in_array('Super Admin', $roles)) {
                $validator->errors()->add('roles', 'failed to update');
                return;
            }
        });
    }
}
