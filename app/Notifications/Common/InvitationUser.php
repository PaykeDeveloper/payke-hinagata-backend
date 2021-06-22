<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Notifications\Common;

use App\Models\Common\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationUser extends Notification
{
    use Queueable;

    private Invitation $invitation;
    private string $token;

    public function __construct(Invitation $invitation, string $token)
    {
        $this->invitation = $invitation;
        $this->token = $token;
    }

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $invitation = $this->invitation;
        $origin = config('constant.frontend_origin');
        $url = "$origin/register?id=$invitation->id&token=$this->token";
        return $this->buildMailMessage($url);
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }

    protected function buildMailMessage(string $url): MailMessage
    {
        /** @var string $subject */
        $subject = __('Invitation instructions');
        /** @var string $action */
        $action = __('Sign up');
        return (new MailMessage())
            ->subject($subject)
            ->line(__('You are invited to :name.', ['name' => config('app.name')]))
            ->line(__('Please click the button below to create your account.'))
            ->action($action, $url);
    }
}
