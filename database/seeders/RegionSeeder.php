<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            "Ahafo Region",
            "Ashanti Region",
            "Bono Region",
            "Bono East Region",
            "Central Region",
            "Eastern Region",
            "Greater Accra Region",
            "North East Region",
            "Northern Region",
            "Oti Region",
            "Savannah Region",
            "Upper East Region",
            "Upper West Region",
            "Volta Region",
            "Western Region",
            "Western North Region"
        ];
        foreach ($regions as $region) {
            Region::query()
                ->create(['name' => $region]);
        }
    }
}
