<?php

namespace App\Notifications;

use App\Models\PromotionPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PromotionSuccessNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $promotedName,
        public readonly PromotionPlan $plan,
    ){}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Your Promotion is Now Live")
            ->view('mails.promotion-success', [
                'promotedName' => $this->promotedName,
                'plan' => $this->plan,
                'user' => $notifiable,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
