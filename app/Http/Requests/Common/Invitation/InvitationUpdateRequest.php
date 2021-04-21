<?php

namespace App\Http\Requests\Common\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\Common\InvitationStatus;
use App\Models\Common\UserRole;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class InvitationUpdateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $invitation = $this->route('invitation');
        if ($invitation->status !== InvitationStatus::PENDING) {
            abort(Response::HTTP_METHOD_NOT_ALLOWED);
        }
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
