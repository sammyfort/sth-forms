<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class HubtelService
{
    protected string $appSecret;
    protected string $appId;
    protected PendingRequest $request;

    public function __construct()
    {
        $this->appSecret = env('HUBTEL_SECRET_KEY');
        $this->appId = env('HUBTEL_APP_ID');
        $encoded = base64_encode("{$this->appId}:{$this->appSecret}");
        $this->request = Http::withHeaders([
            'Authorization' => "Basic $encoded",
            'Accept' => 'application/json',
        ]);
    }

    public function initializePayment(array $data)
    {
        $checkoutUrl = "https://payproxyapi.hubtel.com/items/initiate";
        $response = $this->request->post($checkoutUrl, $data);
        if ($response->successful() && isset($response['data']['checkoutUrl'])) {
            return $response['data'];
        }
        return null;
    }
}
