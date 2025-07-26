<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;


class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'job_type' => $this->faker->randomElement(['Full time', 'Part time', 'Contract', 'Internship']),
            'work_mode' => $this->faker->randomElement(['onsite', 'remote', 'hybrid']),
            'town' => $this->faker->optional()->city(),
            'region_id' => Region::query()->inRandomOrder()->first()->id,
            'salary' => $this->faker->optional()->randomElement([
                'GH₵1,200 - GH₵1,800',
                'Negotiable',
                'GH₵800 - GH₵1,000',
                'Based on experience'
            ]),
            'deadline' => $this->faker->dateTimeBetween('+7 days', '+30 days'),
        ];
    }
}
