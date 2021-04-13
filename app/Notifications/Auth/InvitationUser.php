<?php

namespace App\Notifications\Auth;

use App\Models\Auth\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationUser extends Notification
{
    use Queueable;

    private string $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param Invitation $invitation
     * @return array
     */
    public function via(Invitation $invitation): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param Invitation $invitation
     * @return MailMessage
     */
    public function toMail(Invitation $invitation): MailMessage
    {
        $name = $invitation->user->name;
        $origin = config('constant.frontend_origin');
        $url = "$origin/register?token={$this->token}";
        return $this->buildMailMessage($name, $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param Invitation $invitation
     * @return array
     */
    public function toArray(Invitation $invitation): array
    {
        return [
            //
        ];
    }

    protected function buildMailMessage(string $name, string $url): MailMessage
    {
        return (new MailMessage())
            ->subject(__('Invitation instructions'))
            ->line(__('You are invited from :name.', ['name' => $name]))
            ->line(__('Please click the button below to create your account.'))
            ->action(__('Sign up'), $url);
    }
}
