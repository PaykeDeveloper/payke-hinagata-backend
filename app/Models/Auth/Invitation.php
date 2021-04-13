<?php

namespace App\Models\Auth;

use App\Models\User;
use App\Notifications\InvitationUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin IdeHelperInvitation
 */
class Invitation extends Model
{
    use HasFactory;
    use Notifiable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sendInvitationNotification(string $token, string $locale)
    {
        $this->notify((new InvitationUser($token))->locale($locale));
    }
}
