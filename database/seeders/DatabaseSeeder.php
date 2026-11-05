<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use App\Models\AddOn;
use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        Admin::firstOrCreate(
            ['email' => 'admin@italianpizza.com'],
            [
                'name' => 'Italian Pizza Hut Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Customer
        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Seed Restaurant Tables (Table 1 to Table 10)
        for ($i = 1; $i <= 10; $i++) {
            RestaurantTable::firstOrCreate(
                ['table_number' => "Table $i"],
                [
                    'capacity' => ($i % 3 == 0) ? 6 : (($i % 2 == 0) ? 2 : 4),
                    'status' => 'available',
                ]
            );
        }

        // 4. Seed Add Ons
        $addons = [
            ['name' => 'Cheese', 'icon' => '🧀', 'price' => 50],
            ['name' => 'Chicken', 'icon' => '🍗', 'price' => 60],
            ['name' => 'Beef', 'icon' => '🥩', 'price' => 80],
            ['name' => 'Mushrooms', 'icon' => '🍄', 'price' => 40],
            ['name' => 'Extra Sauce', 'icon' => '🥫', 'price' => 30],
        ];

        foreach ($addons as $addon) {
            AddOn::firstOrCreate(['name' => $addon['name']], $addon);
        }

        // 5. Seed Complete Italian Pizza & Restaurant Menu
        $this->call(ItalianPizzaMenuSeeder::class);
    }
}
