<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'slug' => $this->faker->unique()->slug(),
            'price' => $this->faker->randomFloat(2, 10, 1000),  // Price between 10 and 1000 with 2 decimal points
            'stock' => $this->faker->numberBetween(0, 100),  // Random stock count
            'status' => $this->faker->randomElement(['available', 'unavailable']),
            'category_id' => Category::factory(),  // Creating a related category
        ];
    }
}
