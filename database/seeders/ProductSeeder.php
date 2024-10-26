<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $totalCategories = Category::count();

        Product::factory(20)->create()->each(function (Product $product) {
            // Random category
            $randomCategoryCount = rand(1, 3);

            $categories = Category::inRandomOrder()->take($randomCategoryCount)->get();

            // Attach pivot table
            $product->categories()->attach($categories);
        });
    }
}
