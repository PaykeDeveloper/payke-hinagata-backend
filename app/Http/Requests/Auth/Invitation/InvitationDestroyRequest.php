<?php

namespace App\Http\Requests\Auth\Invitation;

use App\Models\Auth\InvitationStatus;
use Symfony\Component\HttpFoundation\Response;

class InvitationDestroyRequest extends InvitationShowRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $invitation = $this->route('invitation');
        if ($invitation->status !== InvitationStatus::PENDING) {
            abort(Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}
