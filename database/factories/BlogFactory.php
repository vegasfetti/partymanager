<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'subtitle' => $this->faker->sentence(),
            'slug' => Str::slug($title),
            'image' => 'blog_upload/no-photo.png',
            'text' => $this->faker->paragraphs(5, true),
            'published_at' => now(),
            'meta_title' => $title,
            'meta_description' => $this->faker->text(150),
        ];
    }
}
