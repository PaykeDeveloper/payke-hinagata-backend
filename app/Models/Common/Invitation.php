<?php

namespace App\Models\Common;

use App\Models\User;
use App\Notifications\Common\InvitationUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Notification;

/**
 * @mixin IdeHelperInvitation
 */
class Invitation extends Model
{
    use HasFactory;

    protected $attributes = [
        'status' => InvitationStatus::Pending,
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
        'status' => InvitationStatus::class,
    ];

    public function sendInvitationNotification(string $token, string $locale): void
    {
        Notification::route('mail', $this->email)
            ->notify((new InvitationUser($this, $token))->locale($locale));
    }

    public function approved(): self
    {
        $this->status = InvitationStatus::Approved;
        $this->save();
        return $this;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
