<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Home Appliances',
            'Fashion',
            'Beauty & Personal Care',
            'Books',
            'Toys & Games',
            'Sports & Outdoors',
            'Automotive',
            'Groceries',
            'Health & Wellness',
            'Mobile Accessories',
            'Computers & Laptops',
            'Office Supplies',
            'Pet Supplies',
            'Baby Products',
            'Footwear',
            'Watches & Jewelry',
            'Furniture',
            'Kitchenware',
            'Gardening Tools',
        ];

        foreach ($categories as $category) {
            ProductCategory::query()->create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }
    }
}
