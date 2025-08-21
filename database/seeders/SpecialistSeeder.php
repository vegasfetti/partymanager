<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\Specialist;
use App\Models\SpecImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialist::factory()
            ->count(60)
            ->create()
            ->each(function ($specialist) {
                // Для каждого специалиста от 3 до 10 изображений (SpecImage)
                $countImages = rand(3, 10);
                for ($i = 0; $i < $countImages; $i++) {
                    SpecImage::create([
                        'image' => 'spec_upload/no-photo.png',
                        'specialist_id' => $specialist->id,
                    ]);
                }

                // Для каждого специалиста от 10 до 30 изображений в портфолио
                $countPortfolio = rand(10, 30);
                for ($i = 0; $i < $countPortfolio; $i++) {
                    Portfolio::create([
                        'image' => 'portfolio_upload/no-photo.png',
                        'specialist_id' => $specialist->id,
                    ]);
                }
            });
            
    }
}
