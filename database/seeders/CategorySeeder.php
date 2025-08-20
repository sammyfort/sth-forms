<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Studies Sponsored by STH / BMC Budgets',
                'price' => 300,
                'description' => 'Research studies funded directly by the St. Thomas Hospital or its internal budget allocations.',
            ],
            [
                'name' => 'Studies Sponsored by Local Ghanaian Organisations',
                'price' => 300,
                'description' => 'Research projects supported by local Ghanaian institutions, foundations, or non-profits.',
            ],
            [
                'name' => 'Studies with International Funding (US$15,000.00 – 500,000.00)',
                'price' => 300,
                'description' => 'Research studies funded by international sources with budgets ranging from US$15,000 to US$500,000.',
            ],
            [
                'name' => 'Studies with International Funding(<US$ 15,000.00)',
                'price' => 300,
                'description' => 'Smaller-scale international research projects with funding under US$15,000.',
            ],
            [
                'name' => 'Studies with Substantial International Grants/Contracts Research (>US$500,000.00)',
                'price' => 300,
                'description' => 'High-value international research projects exceeding US$500,000 in funding.',
            ],
            [
                'name' => 'Non-Ghanaian Investigators with no External Support',
                'price' => 300,
                'description' => 'Visiting researchers from outside Ghana conducting studies without any external financial support.',
            ],
            [
                'name' => 'Ghanaian Lecturers/Professionals (Non-STH Employees Without Funding)',
                'price' => 300,
                'description' => 'Local lecturers or professionals who are not STH employees and conducting research without funding.',
            ],
            [
                'name' => 'Ghanaian Lecturers/Professionals (STH Employees Without Funding)',
                'price' => 300,
                'description' => 'STH staff members undertaking research without receiving additional funding.',
            ],
            [
                'name' => 'Post Graduate Students with International Funding',
                'price' => 300,
                'description' => 'Postgraduate students supported by international grants or scholarships.',
            ],
            [
                'name' => 'Post Graduate Students with Local Funding',
                'price' => 300,
                'description' => 'Postgraduate students conducting research with funding from local sources.',
            ],
            [
                'name' => 'Post Graduate Students (Without Funding)',
                'price' => 300,
                'description' => 'Postgraduate students undertaking research without any financial support.',
            ],
            [
                'name' => 'Undergraduate Students',
                'price' => 150,
                'description' => 'Undergraduate students conducting research projects as part of their studies.',
            ],
            [
                'name' => 'Non-Ghanaian Students with no External Support',
                'price' => 300,
                'description' => 'Visiting students from abroad performing research without any external funding.',
            ],
            [
                'name' => 'Ghanaian Lecturers/Professionals (With Funding <US$5,000.00)',
                'price' => 300,
                'description' => 'Local lecturers or professionals conducting research with minor funding below US$5,000.',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->create($category);
        }
    }

}
