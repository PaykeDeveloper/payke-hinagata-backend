<?php

namespace App\Http\Requests\Common\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\Common\UserRole;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class InvitationUpdateRequest extends FormRequest
{
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Invitation $invitation */
            $invitation = $this->route('invitation');
            if ($invitation->status !== InvitationStatus::Pending) {
                $validator->errors()->add('', trans('validation.not_in', [
                    'attribute' => __('status'),
                ]));
            }
        });
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'role_names' => ['array'],
            'role_names.*' => ['string', Rule::in(UserRole::all())],
        ];
    }
}
