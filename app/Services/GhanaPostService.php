<?php

namespace App\Services;

use App\DTO\GpsAddressDTO;
use Illuminate\Support\Facades\Http;

class GhanaPostService
{
    public static function getLocationByGPS(string $gps): GpsAddressDTO
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic '.env('GPGPS_AUTHORIZATION'),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://ghanapostgps.sperixlabs.org/get-location', [
            'address' => $gps,
        ]);
        $resJson = $response->json();
        return new GpsAddressDTO($resJson['data']['Table'][0] ?? []);
    }
}
