<?php

namespace Database\Seeders;

use App\Models\PromotionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'description' => 'We will advertise your signboard for 7 days, helping you to reach more customers.',
                'price' => 0.2,
                'number_of_days' => 7,
            ],
            [
                'name' => 'Commercial',
                'description' => 'We will advertise your signboard for 15 days, helping you to reach more customers.',
                'price' => 0.3,
                'number_of_days' => 15,
            ],
            [
                'name' => 'Pro',
                'description' => 'We will advertise your signboard for 30 days, helping you to reach more customers.',
                'price' => 0.4,
                'number_of_days' => 30,
            ],
        ];

        foreach ($plans as $plan) {
            PromotionPlan::query()->create($plan);
        }
    }
}
