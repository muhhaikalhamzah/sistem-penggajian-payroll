<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['title' => 'Manager', 'min_salary' => 10000000, 'max_salary' => 20000000],
            ['title' => 'Staff', 'min_salary' => 5000000, 'max_salary' => 10000000],
        ];

        foreach ($positions as $pos) {
            Position::create($pos);
        }
    }
}
