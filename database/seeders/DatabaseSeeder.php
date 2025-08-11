<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $r = Restaurant::firstOrCreate(
            ['slug' => 'demo'],
            [
                'name' => 'Demo Restoran',
                'phone' => '+998XX',
                'address' => 'Tashkent',
                'plan' => 'free',
                'timezone' => 'Asia/Tashkent',
                'status' => 'active',
            ]
        );

        $cat = MenuCategory::firstOrCreate([
            'restaurant_id' => $r->id,
            'name' => 'Burgerlar',
        ], ['sort' => 1]);

        foreach ([
            ['Cheeseburger', 25000],
            ['Double Burger', 39000],
            ['Chicken Burger', 28000],
        ] as [$name, $price]) {
            MenuItem::firstOrCreate([
                'restaurant_id' => $r->id,
                'category_id' => $cat->id,
                'name' => $name,
            ], [
                'price' => $price * 100 / 100, // store in tiyin if needed
                'sku' => Str::slug($name),
                'is_active' => true,
            ]);
        }
    }
}
