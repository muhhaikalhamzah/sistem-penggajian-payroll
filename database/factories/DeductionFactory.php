<?php

namespace Database\Factories;

use App\Models\Deduction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deduction>
 */
class DeductionFactory extends Factory
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
            'name' => fake()->randomElement(['Potongan BPJS', 'Potongan Koperasi', 'Potongan Keterlambatan']),
            'amount' => fake()->numberBetween(10000, 200000),
            'type' => fake()->randomElement(['Fixed', 'Variable']),
        ];
    }
}
