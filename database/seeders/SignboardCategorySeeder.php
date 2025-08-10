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
        $categories = [
            'Grocery Store',
            'Pharmacy',
            'Fashion Boutique',
            'Barbering Salon',
            'Hair Salon',
            'Electronics Shop',
            'Mobile Money Vendor',
            'Printing Press',
            'Cold Store',
            'Furniture Store',
            'Restaurant',
            'Hardware Store',
            'Auto Spare Parts',
            'Bookshop',
            'Catering Services',
            'Photography Studio',
            'Internet Café',
            'Building Contractor',
            'Travel & Tour Agency',
            'Real Estate Agency'
        ];

        foreach ($categories as $category) {
            SignboardCategory::query()->create(['name' => $category]);
        }

    }
}
