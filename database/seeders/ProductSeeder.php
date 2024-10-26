<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $totalCategories = Category::count();

        Product::factory(20)->create()->each(function (Product $product) {
            // Random order count
            $randomCount = rand(1, 3);
            $orders = Order::inRandomOrder()->take($randomCount)->get();

            $categories = Category::inRandomOrder()->take($randomCount)->get();

            // Random values for price and quantity
            $attachData = [];
            foreach ($orders as $order) {
                $attachData[$order->id] = [
                    'price' => rand(100, 1000),
                    'quantity' => rand(1, 10)
                ];
            }

            // Attach pivot table with additional data
            $product->orders()->attach($attachData);
            $product->categories()->attach($categories);
        });
    }


}
