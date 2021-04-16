<?php

namespace App\Http\Requests\Common\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\Common\InvitationStatus;
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
