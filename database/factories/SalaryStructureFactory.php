<?php

namespace Database\Factories;

use App\Models\SalaryStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalaryStructure>
 */
class SalaryStructureFactory extends Factory
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
            'basic_salary' => fake()->numberBetween(3000000, 15000000),
            'effective_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-01'),
        ];
    }
}
