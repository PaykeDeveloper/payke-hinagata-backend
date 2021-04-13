<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InvitationRequest;
use App\Models\Auth\Invitation;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    public function store(InvitationRequest $request): Response
    {
        $token = \Str::random(60);
        $attributes = $request->all() + ['token' => $token];
        $invitation = Invitation::create($attributes);
        $invitation->sendInvitationNotification($token, $request->getPreferredLanguage());
        return response($invitation);
    }
}
