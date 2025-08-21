<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\City;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialist>
 */
class SpecialistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {



        // Сначала выбираем случайную категорию
        $category = Category::inRandomOrder()->first();

        // Затем выбираем случайную подкатегорию, связанную с этой категорией
        $subcategory = Subcategory::where('category_id', $category->id)
            ->inRandomOrder()
            ->first();



        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'video_link' => fake()->optional()->url(),
            'price' => fake()->randomNumber(6),
            'price_type' => fake()->randomElement(['per_hour', 'per_day', 'per_service']),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'vkontacte' => fake()->optional()->url(),
            'telegram' => fake()->optional()->userName(),
            'website' => fake()->optional()->url(),
            'price_text' => fake()->sentence(),
            'experience' => fake()->randomElement(['less_than_1', '1_3_years', '3_5_years', 'more_than_5']),
            'subject_type' => fake()->randomElement(['individual', 'company']),
            'is_contract' => fake()->boolean(),
            'status' => fake()->randomElement(['on_moderation', 'verify', 'canceled']),

            // 'city_id' => City::inRandomOrder()->value('id'),
            'city_id' => '2',
            'user_id' => User::inRandomOrder()->value('id'),
            'category_id' => $category->id,
            'subcategory_id' => $subcategory?->id, // может быть null, если нет привязанных подкатегорий
        ];
    }
}
