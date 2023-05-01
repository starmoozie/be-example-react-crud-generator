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
        $name = $random === 1 ? $this->faker->regexify('[A-Za-z]{' . mt_rand(4, 6) . '}') : $this->faker->words($random, true);
        return [
            'name' => $name,
            'product_category_id' => ProductCategory::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'buy_price' => fake()->numberBetween(10000, 100000)
        ];
    }
}
