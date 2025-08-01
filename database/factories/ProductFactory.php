<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $towns = ['Accra', 'Kumasi', 'Tamale', 'Takoradi', 'Sunyani', 'Ho', 'Cape Coast', 'Bolgatanga'];

        return [
            'name' => $this->faker->words(3, true),
            'short_description' => $this->faker->sentence,
            'description' => $this->faker->paragraph(10),
            'website' => $this->faker->optional()->url,
            'price' => $this->faker->randomFloat(2, 10, 10000),
            'is_negotiable' => $this->faker->boolean(30),
            'first_mobile' => $this->faker->phoneNumber,
            'second_mobile' => $this->faker->optional()->phoneNumber,
            'whatsapp_mobile' => $this->faker->optional()->phoneNumber,
            'town' => $this->faker->randomElement($towns),
        ];
    }
}
