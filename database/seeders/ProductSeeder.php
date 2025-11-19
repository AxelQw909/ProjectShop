<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Очищаем таблицу перед заполнением
        DB::table('products')->delete();

        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Новый iPhone 15 Pro с титановым корпусом и мощным процессором A17 Pro.',
                'price' => 99990.00,
                'category_id' => 1,
                'stock' => 50,
                'image' => null
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'slug' => 'samsung-galaxy-s24',
                'description' => 'Флагманский смартфон Samsung с искусственным интеллектом.',
                'price' => 79990.00,
                'category_id' => 1,
                'stock' => 30,
                'image' => null
            ],
            [
                'name' => 'MacBook Air M2',
                'slug' => 'macbook-air-m2',
                'description' => 'Легкий и мощный ноутбук от Apple с чипом M2.',
                'price' => 129990.00,
                'category_id' => 2,
                'stock' => 20,
                'image' => null
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'slug' => 'sony-wh-1000xm5',
                'description' => 'Беспроводные наушники с шумоподавлением премиум-класса.',
                'price' => 29990.00,
                'category_id' => 4,
                'stock' => 100,
                'image' => null
            ],
            [
                'name' => 'Samsung QLED 4K',
                'slug' => 'samsung-qled-4k',
                'description' => 'Телевизор с квантовыми точками и разрешением 4K.',
                'price' => 89990.00,
                'category_id' => 3,
                'stock' => 15,
                'image' => null
            ],
            [
                'name' => 'Чехол для iPhone',
                'slug' => 'iphone-case',
                'description' => 'Защитный чехол для iPhone с прозрачным дизайном.',
                'price' => 1990.00,
                'category_id' => 5,
                'stock' => 200,
                'image' => null
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}