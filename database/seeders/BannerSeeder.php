<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Banner::factory()->count(30)->create();

        Banner::create([
            'title' => 'Большой выбор специалистов',
            'subtitle' => 'Выбирайте, кто вам больше подходит',
            'link' => '/specialists',
            'image' => 'banner_upload/main.webp',
            'status' => '1',
            'is_promo' => '0',
            'type' => 'main',
            'city_id'=> null,
        ]);

        Banner::create([
            'title' => 'Умное бронирование',
            'subtitle' => 'Умное бронирование уже доступно',
            'link' => '/booking',
            'image' => 'banner_upload/main.webp',
            'status' => '1',
            'is_promo' => '0',
            'type' => 'specialists',
            'city_id'=> null,
        ]);
    }
}
