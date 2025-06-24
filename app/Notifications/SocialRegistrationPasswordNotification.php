<?php

namespace App\Notifications;

use App\Enums\SocialAuth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SocialRegistrationPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $password, private readonly SocialAuth $platform)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Your Login Password")
            ->markdown('mails.social-registration-password', [
                'user' => $notifiable,
                'password' => $this->password,
                'platform' => $this->platform->value,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
