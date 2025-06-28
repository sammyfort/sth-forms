<?php

namespace Database\Seeders;

use App\Models\SignboardCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SignboardCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SignboardCategory::factory(10)->create();
    }
}
