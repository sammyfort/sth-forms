<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Signboard>
 */
class SignboardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'town' => $this->faker->city(),
            'landmark' => $this->faker->streetAddress(),
            'street' => $this->faker->streetName(),
            'blk_number' => $this->faker->streetSuffix(),
            'gps' => $this->faker->creditCardNumber(),
            'created_by_id' => 1,
        ];
    }
}
