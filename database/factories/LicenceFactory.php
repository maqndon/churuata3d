<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Licence>
 */
class LicenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'short_description' => fake()->unique()->sentence(),
            'description' => fake()->unique()->sentence(),
            'link' => fake()->unique()->imageUrl(),
            'icon' => fake()->unique()->word() . '.png',
            'logo' => fake()->unique()->word() . '.png',
        ];
    }
}