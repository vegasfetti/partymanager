<?php

namespace Database\Factories;

use App\Models\Specialist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'text' => $this->faker->paragraph(3),
            'rating' => $this->faker->numberBetween(1, 5),
            'specialist_id' => Specialist::inRandomOrder()->value('id') ?? Specialist::factory(),
            'user_id' => User  ::inRandomOrder()->value('id') ?? User::factory(),
            'status' => 'verify', 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
