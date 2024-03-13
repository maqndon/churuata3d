<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Licence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        return [
            'licence_id' => Licence::factory()->create()->id,
            'title' => fake()->unique()->name(),
            'slug' => fake()->unique()->slug(),
            'sku' => fake()->unique()->slug(),
            'excerpt' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'stock' => fake()->randomNumber(),
            'price' => fake()->numberBetween(50, 100),
            'sale_price' => fake()->numberBetween(0, 49),
            'status' => 'Published',
            'is_featured' => false,
            'is_downloadable' => true,
            'is_free' => true,
            'is_printable' => true,
            'is_parametric' => false,
            'related_parametric' => '',
            'downloads' => fake()->randomNumber(),
        ];
    }
}
