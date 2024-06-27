<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => fake()->unique()->name(),
            'site_title' => fake()->unique()->name(),
            'site_description' => fake()->unique()->sentence(),
            'site_logo' => fake()->unique()->word() . '.png',
            'site_google_analytics_tracking_id' => fake()->randomNumber(),
        ];
    }
}
