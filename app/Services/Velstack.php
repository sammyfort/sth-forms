<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Velstack
{
    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function sendQuickSMS($recipient, $message): PromiseInterface|Response
    {
        $key = config('services.velstack.SENDER_ID');
        $sender = config('services.velstack.SENDER_ID');
        $data = [
            'title' => 'SMS Notifications',
            'recipient' => $recipient,
            'sender' => $sender,
            'message' => $message,
            'is_scheduled' => false,
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer $key",
            'Accept' => 'application/json',
        ])->post('https://sms.velstack.com/api/quick/sms', $data);

        $response->throw();
        return $response;
    }

}
