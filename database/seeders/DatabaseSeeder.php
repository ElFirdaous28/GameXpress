<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(10)->create();

        // Create 50 products, each with a related category and 3 images
        Product::factory(50)->create()->each(function ($product) {
            ProductImage::factory(3)->create(['product_id' => $product->id]);
        });
    }
}
