<?php

namespace App\Http\Requests\Auth\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\Auth\InvitationStatus;
use Symfony\Component\HttpFoundation\Response;

class InvitationDestroyRequest extends FormRequest
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
            //
        ];
    }
}
