<?php

namespace Database\Factories;

use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+' . fake()->numberBetween(1, 5) . ' days');
        
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'leave_type' => fake()->randomElement(['Annual', 'Sick', 'Unpaid']),
            'status' => fake()->randomElement(['Pending', 'Approved', 'Rejected']),
        ];
    }
}
