<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductCategory;
use App\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
        $random = $this->faker->numberBetween(1, 3);
        return [
            'name' => $this->faker->words($random, true),
            'product_category_id' => ProductCategory::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'buy_price' => fake()->numberBetween(10000, 100000)
        ];
    }
}
