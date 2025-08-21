<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {



        return [
            'title' => fake()->sentence(),
            'subtitle' => fake()->sentence(),
            'link' => fake()->url(),
            'image' => 'banner_upload/no-photo.png',
            'status' => fake()->boolean(),
            'is_promo' => fake()->boolean(),
            'type' => fake()->randomElement(['main', 'specialists']),
            'city_id' => fake()->boolean()
                ? \App\Models\City::inRandomOrder()->value('id')
                : null,
        ];
    }
}
