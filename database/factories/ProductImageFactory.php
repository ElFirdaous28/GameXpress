<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),  // Create a related product
            'image_path' => $this->faker->imageUrl(640, 480, 'products', true),  // Random image URL for product image
            'is_primary' => $this->faker->boolean(),
        ];
    }
}
