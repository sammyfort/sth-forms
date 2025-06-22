<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Mnotify
{
    private string $quickSMSURL = 'https://api.mnotify.com/api/sms/quick';

    private string $apiKey;
    private string $sender;
    private string $message;

    public function __construct()
    {
        $this->apiKey = env('MNOTIFY_KEY');
        $this->sender = env('MNOTIFY_SENDER_ID');
    }

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMnotify();
        $receiverMobile = $notifiable->routeNotificationFor('mnotify') ?? $notifiable->tel;
        $this->sendQuickSMS($receiverMobile, $message);
    }

    public function sendQuickSMS(array|string $recipients, $message=null): void{
        $url = "{$this->quickSMSURL}/?key={$this->apiKey}";;
        if (!is_array($recipients)){
            $recipients = [$recipients];
        }
        $data = [
            'recipient' => $recipients,
            'sender' => $this->sender,
            'message' => $message ?? $this->message,
        ];
        $response = Http::post($url, $data);
        if ($response->clientError()){
            Log::error(" Mnotify Error; error in request: " . $response->toException()->getMessage());
        }
        elseif ($response->serverError()){
            Log::critical('Sending to Mnotify failed; Mnotify server encountered an error: ' . $response->toException()->getMessage());
        }
        else {
            Log::critical('Sending to Mnotify failed; unexpected error occurred: ' . $response->toException()->getMessage());
        }
    }
}
