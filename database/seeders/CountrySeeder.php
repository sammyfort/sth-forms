<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = json_decode(file_get_contents(database_path('seeders/data/countries.json')));
        $regions = json_decode(file_get_contents(database_path('seeders/data/states.json')));

        foreach ($countries as $country) {
            Country::query()->create([
                'id' => $country->id,
                'name' => $country->name,
                'iso2' => $country->iso2,
                'iso3' => $country->iso3,
                'phonecode' => $country->phonecode,
                'currency' => $country->currency,
                'currency_symbol' => $country->currency_symbol,
            ]);
        }

        foreach ($regions as $region) {
            Region::query()->create([
                'id' => $region->id,
                'name' => $region->name,
                'country_id' => $region->country_id,
                'longitude' => $region->longitude,
                'latitude' => $region->latitude,
            ]);
        }
    }
}
