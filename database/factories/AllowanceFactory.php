<?php

namespace Database\Factories;

use App\Models\Allowance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Allowance>
 */
class AllowanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'name' => fake()->randomElement(['Tunjangan Makan', 'Tunjangan Transport', 'Tunjangan Jabatan']),
            'amount' => fake()->numberBetween(50000, 1000000),
            'type' => fake()->randomElement(['Tetap', 'Variabel']),
        ];
    }
}
