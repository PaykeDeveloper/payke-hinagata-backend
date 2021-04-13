<?php

namespace App\Http\Requests\Auth\Invitation;

use App\Http\Requests\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class InvitationShowRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $invitation = $this->route('invitation');
        if ($invitation->user->id !== $this->user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
