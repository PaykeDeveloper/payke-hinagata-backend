<?php

namespace App\Notifications\Auth;

use App\Models\Auth\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationUser extends Notification
{
    use Queueable;

    private Invitation $invitation;
    private string $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation, string $token)
    {
        $this->invitation = $invitation;
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
        $invitation = $this->invitation;
        $origin = config('constant.frontend_origin');
        $url = "$origin/register?id={$invitation->id}&token={$this->token}";
        return $this->buildMailMessage($invitation->create_user->name, $url);
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
        /** @var string $subject */
        $subject = __('Invitation instructions');
        /** @var string $action */
        $action = __('Sign up');
        return (new MailMessage())
            ->subject($subject)
            ->line(__('You are invited from :name.', ['name' => $username]))
            ->line(__('Please click the button below to create your account.'))
            ->action($action, $url);
    }
}
