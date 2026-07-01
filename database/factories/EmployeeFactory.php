<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_number' => 'EMP-' . fake()->unique()->numberBetween(100, 9999),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'ptkp_status' => fake()->randomElement(['TK/0', 'TK/1', 'K/0', 'K/1', 'K/2']),
            'join_date' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'department_id' => \App\Models\Department::factory(),
            'position_id' => \App\Models\Position::factory(),
            'user_id' => null,
        ];
    }
}
