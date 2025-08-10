<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Information Technology',
            'Healthcare',
            'Education & Training',
            'Accounting & Finance',
            'Sales & Marketing',
            'Engineering',
            'Construction',
            'Customer Service',
            'Administration & Office Support',
            'Hospitality & Tourism',
            'Legal',
            'Logistics & Transportation',
            'Human Resources',
            'Media & Communication',
            'Agriculture',
            'Creative Arts & Design',
            'Manufacturing',
            'Security Services',
            'Real Estate',
            'NGO & Social Work',
        ];

        foreach ($categories as $name) {
            JobCategory::query()->create(['name' => $name]);
        }
    }
}
