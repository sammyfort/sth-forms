<?php

namespace App\DTO;

class GpsAddressDTO
{
    public string $area;
    public string $addressV1;
    public float $centerLatitude;
    public float $centerLongitude;
    public string $district;
    public float $eastLat;
    public float $eastLong;
    public string $gpsName;
    public float $northLat;
    public float $northLong;
    public string $postCode;
    public string $region;
    public float $southLat;
    public float $southLong;
    public string $street;
    public float $westLat;
    public float $westLong;

    public function __construct(array $data)
    {
        $this->area = $data['Area'] ?? '';
        $this->addressV1 = $data['AddressV1'] ?? '';
        $this->centerLatitude = (float) ($data['CenterLatitude'] ?? 0.0);
        $this->centerLongitude = (float) ($data['CenterLongitude'] ?? 0.0);
        $this->district = $data['District'] ?? '';
        $this->eastLat = (float) ($data['EastLat'] ?? 0.0);
        $this->eastLong = (float) ($data['EastLong'] ?? 0.0);
        $this->gpsName = $data['GPSName'] ?? '';
        $this->northLat = (float) ($data['NorthLat'] ?? 0.0);
        $this->northLong = (float) ($data['NorthLong'] ?? 0.0);
        $this->postCode = $data['PostCode'] ?? '';
        $this->region = $data['Region'] ?? '';
        $this->southLat = (float) ($data['SouthLat'] ?? 0.0);
        $this->southLong = (float) ($data['SouthLong'] ?? 0.0);
        $this->street = $data['Street'] ?? '';
        $this->westLat = (float) ($data['WestLat'] ?? 0.0);
        $this->westLong = (float) ($data['WestLong'] ?? 0.0);
    }
}
