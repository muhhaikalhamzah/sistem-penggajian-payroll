<?php

namespace Database\Factories;

use App\Models\TaxRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaxRecord>
 */
class TaxRecordFactory extends Factory
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
            'taxable_income' => fake()->randomFloat(2, 5000000, 100000000),
            'pph21_amount' => fake()->randomFloat(2, 50000, 5000000),
            'period' => '10-2023',
        ];
    }
}
