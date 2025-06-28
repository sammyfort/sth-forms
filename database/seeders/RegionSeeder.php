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
            "Ahafo",
            "Ashanti",
            "Bono",
            "Bono East",
            "Central",
            "Eastern",
            "Greater Accra",
            "North East",
            "Northern",
            "Oti",
            "Savannah",
            "Upper East",
            "Upper West",
            "Volta",
            "Western",
            "Western North"
        ];
        foreach ($regions as $region) {
            Region::query()
                ->create(['name' => $region]);
        }
    }
}
