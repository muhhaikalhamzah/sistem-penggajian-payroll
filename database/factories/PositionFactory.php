<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = fake()->numberBetween(3000000, 10000000);
        return [
            'title' => fake()->unique()->jobTitle(),
            'min_salary' => $min,
            'max_salary' => $min + fake()->numberBetween(1000000, 5000000),
        ];
    }
}
