<?php

namespace Database\Seeders;

use App\Models\SignboardSubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SignboardSubscriptionPlanSeeder extends Seeder
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
                'price' => 50,
                'number_of_days' => 7,
            ],
            [
                'name' => 'Commercial',
                'description' => 'We will advertise your signboard for 15 days, helping you to reach more customers.',
                'price' => 90,
                'number_of_days' => 15,
            ],
            [
                'name' => 'Pro',
                'description' => 'We will advertise your signboard for 30 days, helping you to reach more customers.',
                'price' => 170,
                'number_of_days' => 30,
            ],
        ];

        foreach ($plans as $plan) {
            SignboardSubscriptionPlan::query()->create($plan);
        }
    }
}
