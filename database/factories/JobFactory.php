<?php

namespace Database\Factories;

use App\Enums\JobMode;
use App\Enums\JobType;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;


class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'company_name' => $this->faker->company(),
            'description' => $this->faker->paragraphs(10, true),
            'summary' => $this->faker->sentence(20),
            'job_type' => $this->faker->randomElement(JobType::toArray()),
            'work_mode' => $this->faker->randomElement(JobMode::toArray()),
            'town' => $this->faker->optional()->city(),
            'salary' => $this->faker->optional()->randomElement([
                'GH₵1,200 - GH₵1,800',
                'Negotiable',
                'GH₵800 - GH₵1,000',
                'Based on experience'
            ]),
            'deadline' => $this->faker->dateTimeBetween('+7 days', '+30 days'),
            'how_to_apply' => 'Send your CV and cover letter to: kofibusy@gmail.com',
            'application_link' => $this->faker->randomElement(['https://www.google.com/', null]),
        ];
    }
}
