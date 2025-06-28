<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
            'description' => $this->faker->realText(),
            'mobile' => $this->faker->phoneNumber(),
            'facebook' => $this->faker->url(),
            'x' => $this->faker->url(),
            'linkedin' => $this->faker->url(),
            'instagram' => $this->faker->url(),
            'created_by_id' => 1,
        ];
    }
}
