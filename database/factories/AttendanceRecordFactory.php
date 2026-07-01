<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = '08:' . str_pad(fake()->numberBetween(0, 30), 2, '0', STR_PAD_LEFT);
        $checkOutHour = fake()->numberBetween(17, 19);
        $checkOutMin = str_pad(fake()->numberBetween(0, 59), 2, '0', STR_PAD_LEFT);
        $checkOut = $checkOutHour . ':' . $checkOutMin;
        $overtime = max(0, $checkOutHour - 17);

        return [
            'employee_id' => \App\Models\Employee::factory(),
            'record_date' => fake()->unique()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'overtime_hours' => $overtime,
            'status' => fake()->randomElement(['Present', 'Present', 'Present', 'Present', 'Absent', 'Leave']), // heavily weighted to present
        ];
    }
}
