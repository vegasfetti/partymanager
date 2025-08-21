<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Админ вручную
        User::create([
            'name' => 'Анатолий',
            'email' => 'dairukin.a@mail.ru',
            'password' => Hash::make('Teaz4417.'),
            'role' => 'admin',
            'image' => 'profile_upload/default-img.png',
            'email_verified_at' => now(),
        ]);

        // 2. 10 рандомных пользователей
        User::factory()->count(10)->create();
    }
}
