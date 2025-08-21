<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Фотограф',
                'image' => 'cat_upload/photograph.webp',
            ],
            [
                'name' => 'Ведущий',
                'image' => 'cat_upload/vedushii.webp',
            ],
            [
                'name' => 'Аниматор',
                'image' => 'cat_upload/animator.webp',
            ],
            [
                'name' => 'Артист',
                'image' => 'cat_upload/artist.webp',
            ],
            [
                'name' => 'Площадка',
                'image' => 'cat_upload/ploshadka.webp',
            ],
            [
                'name' => 'Видеограф',
                'image' => 'cat_upload/videograph.webp',
            ],
            [
                'name' => 'Диджей',
                'image' => 'cat_upload/dj.webp',
            ],
        ];

        foreach ($categories as $cat) {
            $category = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'image' => $cat['image'], // путь к картинке
            ]);

            for ($i = 1; $i <= 5; $i++) {
                Subcategory::create([
                    'name' => "{$cat['name']} - Подкатегория {$i}",
                    'slug' => Str::slug("{$cat['name']}-sub-{$i}"),
                    'category_id' => $category->id,
                ]);
            }
        }

    }
}
