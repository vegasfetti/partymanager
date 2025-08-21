<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'Москва', 'slug' => 'moscow'],
            ['name' => 'Чебоксары', 'slug' => 'cheboksary'],
            ['name' => 'Казань', 'slug' => 'kazan'],
            ['name' => 'Санкт-Петербург', 'slug' => 'spb'],
            ['name' => 'Новосибирск', 'slug' => 'novosibirsk'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
