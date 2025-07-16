<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Plumber',
            'Electrician',
            'Carpenter',
            'Painter',
            'Mechanic',
            'Tailor',
            'Hairdresser',
            'Barber',
            'AC Technician',
            'Refrigeration Technician',
            'Bricklayer',
            'Welder',
            'Mason',
            'Interior Decorator',
            'Event Decorator',
            'Phone Repairer',
            'Computer Technician',
            'Dry Cleaner',
            'CCTV Installer',
            'Solar Installer'
        ];

        foreach ($categories as $category) {
            ServiceCategory::query()->create([
                'name' => $category,
            ]);
        }
    }
}
