<?php

namespace App\Notifications;

use App\Models\User;
use Codebyray\ReviewRateable\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly User $rater,
        public readonly Review $review,
        public readonly string $itemType
    ){}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->view('mails.new-review', [
                'user' => $notifiable,
                'rater' => $this->rater,
                'itemType' => $this->itemType,
                'review' => $this->review,
                'averageRating' => ratingFormat($this->review->ratings()->avg('value')),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
