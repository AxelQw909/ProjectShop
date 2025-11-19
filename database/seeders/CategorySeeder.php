<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Очищаем таблицу перед заполнением
        DB::table('categories')->delete();

        $categories = [
            [
                'name' => 'Смартфоны', 
                'slug' => 'smartphones',
                'description' => 'Современные смартфоны и телефоны'
            ],
            [
                'name' => 'Ноутбуки', 
                'slug' => 'laptops',
                'description' => 'Ноутбуки для работы и игр'
            ],
            [
                'name' => 'Телевизоры', 
                'slug' => 'tvs',
                'description' => 'Телевизоры и мониторы'
            ],
            [
                'name' => 'Наушники', 
                'slug' => 'headphones',
                'description' => 'Наушники и аудиотехника'
            ],
            [
                'name' => 'Аксессуары', 
                'slug' => 'accessories',
                'description' => 'Аксессуары для техники'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}