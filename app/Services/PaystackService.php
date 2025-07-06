<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = env('PAYSTACK_SECRET_KEY');
    }

    /**
     * Initialize a payment transaction
     */
    public function initializePayment(array $data): ?string
    {
        $url = "https://api.paystack.co/transaction/initialize";
        $response = Http::withToken($this->secretKey)
            ->post($url, $data);
        if ($response->successful() && isset($response['data']['authorization_url'])) {
            return $response['data']['authorization_url'];
        }
        return null;
    }

    public function verifyPayment(string $reference): ?object
    {
        $response = Http::withToken($this->secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");
        if ($response->successful() && $response['data']['status'] === 'success') {
            return arrayAndSubToObject($response['data']);
        }
        return null;
    }
}
