<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Очищаем таблицы
        DB::table('carts')->delete();
        DB::table('users')->delete();

        // Создаем администратора
        $admin = User::create([
            'name' => 'Администратор',
            'email' => 'admin@shop.ru',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'address' => 'Москва, ул. Примерная, д. 1'
        ]);

        Cart::create(['user_id' => $admin->id]);

        // Создаем тестового пользователя
        $user = User::create([
            'name' => 'Тестовый Пользователь',
            'email' => 'user@shop.ru',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'address' => 'Санкт-Петербург, ул. Тестовая, д. 5'
        ]);

        Cart::create(['user_id' => $user->id]);

        // Создаем еще одного пользователя
        $user2 = User::create([
            'name' => 'Иван Петров',
            'email' => 'ivan@shop.ru',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'address' => 'Новосибирск, пр. Ленина, д. 10'
        ]);

        Cart::create(['user_id' => $user2->id]);
    }
}