<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferrerRewardNotification extends Notification
{
    use Queueable;

    private int $pointsPerReferral;

    public function __construct(private readonly User $referredUser)
    {
        $this->pointsPerReferral = config('app.points_per_referral');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🎉 Someone joined using your referral link – You’ve earned ".$this->pointsPerReferral." points!")
            ->view('mails.referrer-reward-notification', [
                'referredUser' => $this->referredUser,
                'pointsPerReferral' => $this->pointsPerReferral,
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
