<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationUser extends Notification
{
    use Queueable;

    private string $username;
    private string $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $username, string $token)
    {
        $this->username = $username;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $origin = config('constant.frontend_origin');
        $url = "$origin/register?token={$this->token}";
        return $this->buildMailMessage($this->username, $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }

    protected function buildMailMessage(string $username, string $url): MailMessage
    {
        return (new MailMessage())
            ->subject(__('Invitation instructions'))
            ->line(__('You are invited from :name.', ['name' => $username]))
            ->line(__('Please click the button below to create your account.'))
            ->action(__('Sign up'), $url);
    }
}
