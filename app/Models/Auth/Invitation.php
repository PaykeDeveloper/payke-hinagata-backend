<?php

namespace App\Models\Auth;

use App\Models\User;
use App\Notifications\Auth\InvitationUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Notification;

/**
 * @mixin IdeHelperInvitation
 */
class Invitation extends Model
{
    use HasFactory;

    public const RESOURCE = 'invitation';

    protected $attributes = [
        'status' => InvitationStatus::PENDING,
    ];

    protected $fillable = [
        'name',
        'email',
    ];

    protected $hidden = [
        'token',
        'create_user',
    ];

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName
    public function create_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    private function sendInvitationNotification(string $token, string $locale): void
    {
        Notification::route('mail', $this->email)
            ->notify((new InvitationUser($this, $token))->locale($locale));
    }

    public static function createFromRequest(array $attributes, User $user): Invitation
    {
        $invitation = new self();
        $invitation->fill($attributes);
        $token = Str::random(60);
        $invitation->token = hash('sha256', $token);
        $invitation->created_by = $user->id;
        $invitation->save();
        $invitation->sendInvitationNotification($token, $attributes['locale']);
        return $invitation;
    }

    public function approved(): Invitation
    {
        $this->status = InvitationStatus::APPROVED;
        $this->save();
        return $this;
    }
}
