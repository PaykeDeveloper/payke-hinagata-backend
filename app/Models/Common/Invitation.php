<?php

namespace App\Models\Common;

use App\Models\User;
use App\Notifications\Common\InvitationUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected $guarded = [
        'id',
        'token',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'token',
    ];

    protected $casts = [
        'role_names' => 'array',
    ];

    private function sendInvitationNotification(string $token, string $locale): void
    {
        Notification::route('mail', $this->email)
            ->notify((new InvitationUser($this, $token))->locale($locale));
    }

    public static function createFromRequest(array $attributes, User $user): self
    {
        $invitation = new self();
        $invitation->fill($attributes);
        $token = Str::random(60);
        $invitation->token = hash('sha256', $token);
        $invitation->created_by = $user->id;
        $invitation->save();
        $invitation->sendInvitationNotification($token, $attributes['locale']);
        return $invitation->fresh();
    }

    public function updateFromRequest(mixed $attributes): self
    {
        $this->update($attributes);
        return $this->fresh();
    }

    public function approved(): self
    {
        $this->status = InvitationStatus::APPROVED;
        $this->save();
        return $this;
    }
}
