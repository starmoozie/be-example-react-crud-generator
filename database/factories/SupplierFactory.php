<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'phone' => collect([null, fake()->numerify('62##########')])->random()
        ];
    }

    public function contacts()
    {
        $amount = rand(1, 5);
        $active = rand(1, $amount);

        for ($i = 1; $i <= $amount; $i++) {
            $data[] = [
                'active' => $i === $active,
                'phone' => fake()->numerify('62##########')
            ];
        }

        return $data;
    }
}
