<?php

namespace App\Repositories\Common;

use App\Models\Common\Invitation;
use App\Models\User;
use Illuminate\Support\Str;

class InvitationRepository
{
    public function store(array $attributes, User $user): Invitation
    {
        $invitation = new Invitation($attributes);
        $token = Str::random(60);
        $invitation->token = hash('sha256', $token);
        $invitation->created_by = $user->id;
        $invitation->save();
        $invitation->sendInvitationNotification($token, $attributes['locale']);
        return $invitation->fresh();
    }

    public function update(array $attributes, Invitation $invitation): ?Invitation
    {
        $invitation->update($attributes);
        return $invitation->fresh();
    }
}
