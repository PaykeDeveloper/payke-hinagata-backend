<?php

namespace App\Http\Requests\Common\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use Illuminate\Validation\Validator;

class InvitationDestroyRequest extends FormRequest
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
            //
        ];
    }
}
