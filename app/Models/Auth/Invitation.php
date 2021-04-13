<?php

namespace App\Models\Auth;

use App\Models\User;
use App\Notifications\Auth\InvitationUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperInvitation
 */
class Invitation extends Model
{
    use HasFactory;
    use Notifiable;

    protected $attributes = [
        'status' => InvitationStatus::PENDING,
    ];

    protected $fillable = [
        'email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    private function sendInvitationNotification(string $token, string $locale)
    {
        $this->notify((new InvitationUser($token))->locale($locale));
    }

    public function createFromRequest(array $attributes): Invitation
    {
        $invitation = new self();
        $invitation->fill($attributes);
        $token = Str::random(60);
        $invitation->token = hash('sha256', $token);
        $invitation->save();
        $invitation->sendInvitationNotification($token, $attributes['locale']);
        return $invitation;
    }
}
