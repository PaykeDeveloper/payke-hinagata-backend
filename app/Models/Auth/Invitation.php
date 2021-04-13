<?php

namespace App\Models\Auth;

use App\Models\User;
use App\Notifications\Auth\InvitationUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperInvitation
 */
class Invitation extends Model
{
    use HasFactory;

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
        $username = $this->user->name;
        \Notification::route('mail', $this->email)
            ->notify((new InvitationUser($username, $token))->locale($locale));
    }

    public static function createFromRequest(array $attributes, User $user): Invitation
    {
        $invitation = new self();
        $invitation->fill($attributes);
        $invitation->user_id = $user->id;
        $token = Str::random(60);
        $invitation->token = hash('sha256', $token);
        $invitation->save();
        $invitation->sendInvitationNotification($token, $attributes['locale']);
        return $invitation;
    }
}